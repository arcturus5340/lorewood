from django.conf import settings
from django.contrib import auth
from django.contrib.auth import password_validation
from django.core import exceptions
from django.http.request import HttpRequest
from django.http.response import JsonResponse
from django.shortcuts import redirect, render
from django.utils.translation import gettext as _

import datetime
import logging
import PIL.Image

from app.models import ActivationKey, GlobalSettings, Revenue, Records, ProvidedUser
from .auth import is_verified

logger = logging.getLogger('app')


@is_verified
def cabinet(request: HttpRequest, username: str):
    if request.user.username == username:
        context = {
            'premium': GlobalSettings.objects.get(setting='Premium').value,
        }
        return render(request, 'user/cabinet.html', context)

    logger.warning('Cabinet login fail: An attempt to enter someone else\'s cabinet')
    return redirect('/')


@is_verified
def save_personal_data(request: HttpRequest):
    user = request.user
    try:
        im = PIL.Image.open(request.FILES.get('avatar'))
        width, height = im.size
        new_size = min(height, width)
        left = (width - new_size) / 2
        top = (height - new_size) / 2
        right = (width + new_size) / 2
        bottom = (height + new_size) / 2

        filename = settings.MEDIA_ROOT + '/avatars/cropped/cropped-{}crop.jpg'.format(user.username)

        image = im.crop((left, top, right, bottom))
        image.save(filename)

        user.profile.avatar = '/media/avatars/cropped/cropped-{}crop.jpg'.format(user.username)
    except IOError:
        logger.warning('Image crop fail: File not found')
    except Exception as exc:
        logger.warning('Image crop fail: {}'.format(exc))

    user.first_name = request.POST.get('first_name')
    user.last_name = request.POST.get('last_name')
    user.profile.bio = request.POST.get('bio')
    user.profile.save()
    user.save()

    return redirect("/user/{}/cabinet".format(user.username))


@is_verified
def two_verif_on(request: HttpRequest):
    user = request.user
    if ActivationKey.objects.filter(user=user, is_registration=True).exists:
        user.profile.has_2step_verification = True
        user.save()
        return JsonResponse({
            'status': 'ok',
        })

    logger.info('2-step-verification enable fail: Verification required')
    return JsonResponse({
        'status': 'fail',
        'message': _('Verify your account first'),
    })


@is_verified
def two_verif_off(request):
    user = request.user
    user.profile.two_verif = False
    user.save()
    return redirect('/user/{}/cabinet/list-settings'.format(request.user.username))


@is_verified
def buy(request: HttpRequest, record_id: int):
    from django.utils.translation import gettext as _

    user = request.user
    if not user.profile.is_verified:
        logger.info('Record purchase fail: Verification required')
        return JsonResponse({
            'status': 'fail',
            'message': _('Verify your account first'),
        })

    current_record = Records.objects.get(id=record_id)

    if ProvidedUser.objects.filter(user=user, record=current_record).exists():
        logger.info('Record purchase fail: User is provided with this record')
        return JsonResponse({
            'status': 'fail',
            'message': _('You have already bought this record'),
        })

    if (user.profile.balance - current_record.price) < 0:
        logger.info('Record purchase fail: User balance is not enough')
        return JsonResponse({
            'status': 'fail',
            'message': _('There are not enough funds on your balance'),
        })

    user.profile.balance -= current_record.price
    user.profile.save()

    ProvidedUser.objects.create(user=user, record=current_record)

    obj, _ = Revenue.objects.get_or_create(date=datetime.date.today(), defaults={'income': 0})
    obj.income += current_record.price
    obj.save()

    current_record.sales += 1
    current_record.save()

    return JsonResponse({
        'status': 'ok',
    })


@is_verified
def buy_premium(request):
    from django.utils.translation import gettext as _

    user = request.user

    if not user.profile.is_verified:
        logger.info('Premium  purchase fail: Verification required')
        return JsonResponse({
            'status': 'fail',
            'message': _('Verify your account first'),
        })

    if user.profile.is_premium:
        logger.info('Premium  purchase fail: User have premium')
        return JsonResponse({
            'status': 'fail',
            'message': _('You already have a premium subscription'),
        })

    cost = GlobalSettings.objects.get(setting='Premium').value
    if user.profile.balance - cost < 0:
        logger.info('Premium  purchase fail: User balance is not enough')
        return JsonResponse({
            'status': 'fail',
            'message': _('There are not enough funds on your balance'),
        })

    user.profile.balance -= cost
    user.profile.is_premium = True
    user.save()

    obj, _ = Revenue.objects.get_or_create(date=datetime.date.today(), defaults={'income': 0})
    obj.income += cost
    obj.save()

    return redirect('/user/{}/cabinet/list-buy-premium'.format(request.user.username))


def change_password(request: HttpRequest):
    activation_key = request.POST.get('activation_key')
    if request.user.is_anonymous and not ActivationKey.objects.filter(activation_key=activation_key).exists():
        logger.info('Password change fail: Wrong activation key')
        return JsonResponse({
            'status': 'fail',
            'message': _('Wrong activation key'),
        })

    elif request.user.is_authenticated:
        user = request.user
    else:
        activation_obj = ActivationKey.objects.get(activation_key=activation_key)
        user = activation_obj.user
        activation_obj.delete()

    new_password1 = request.POST.get('new_password1')
    new_password2 = request.POST.get('new_password2')

    if new_password1 != new_password2:
        logger.info('Password change fail: Passwords mismatch')
        return JsonResponse({
            'status': 'fail',
            'message': _('Passwords mismatch'),
        })

    try:
        auth.password_validation.validate_password(new_password1)
    except exceptions.ValidationError as err:
        # TODO: Log on English
        logger.info('Password change fail: {}'.format(err.messages[0]))
        return JsonResponse({
            'status': 'fail',
            'message': err.messages[0],
        })

    try:
        user.set_password(new_password1)
        user.save()
        auth.login(request, user, backend='django.contrib.auth.backends.ModelBackend')
        return JsonResponse({
            'status': 'ok',
        })
    except exceptions.MultipleObjectsReturned:
        logger.warning('DataBase error: Multiple users are returned for one username')
    except exceptions.ObjectDoesNotExist:
        logger.warning('Password change fail: Wrong username')

    return JsonResponse({
        'status': 'fail',
        'message': _('Security error. Contact site administrator'),
    })
