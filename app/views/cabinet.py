from django.conf import settings
from django.contrib import auth
from django.contrib.auth import password_validation
from django.core import exceptions
from django.http.request import HttpRequest
from django.http.response import JsonResponse
from django.shortcuts import redirect, render

import datetime
import logging
import PIL.Image

from app.models import Activation, Global_Settings, Revenue

logger = logging.getLogger('app')


def cabinet(request: HttpRequest, username: str, section: str):
    if request.user.username == username:
        context = {
            'premium': Global_Settings.objects.get(setting='Premium').value,
            'section': section
        }
        return render(request, 'user/cabinet.html', context)

    logger.warning('Cabinet login fail: An attempt to enter someone else\'s cabinet')
    return redirect('/')


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

        user.first_name = request.POST.get('first_name')
        user.last_name = request.POST.get('last_name')
        user.profile.bio = request.POST.get('bio')
        user.profile.avatar = '/media/avatars/cropped/cropped-{}crop.jpg'.format(user.username)
        user.save()

    except IOError:
        logger.warning('Image crop fail: File not found')
    except Exception as exc:
        logger.warning('Image crop fail: {}'.format(exc))

    return redirect("/user/{}/cabinet/default".format(user.username))


def two_verif_on(request: HttpRequest):
    user = request.user
    if Activation.objects.filter(user=user, is_registration=True).exists:
        user.profile.has_2step_verification = True
        user.save()
        return JsonResponse({
            'status': 'ok',
        })

    logger.info('2-step-verification enable fail: Verification required')
    return JsonResponse({
        'status': 'fail',
        'message': '2-step-verifiacion on: Verification required',
    })


def two_verif_off(request):
    user = request.user
    user.profile.two_verif = False
    user.save()
    return redirect('/user/{}/cabinet/list-settings'.format(request.user.username))


def buy_premium(request):
    user = request.user

    if not user.profile.is_verified:
        logger.info('Premium  purchase fail: Verification required')
        return JsonResponse({
            'status': 'fail',
            'message': 'User is not verified',
        })

    if user.profile.is_premium:
        logger.info('Premium  purchase fail: User have premium')
        return JsonResponse({
            'status': 'fail',
            'message': 'User have premium',
        })

    cost = Global_Settings.objects.get(setting='Premium').value
    if user.profile.balance - cost < 0:
        logger.info('Premium  purchase fail: User balance is not enough')
        return JsonResponse({
            'status': 'fail',
            'message': 'Balance is not enough',
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
    if request.user.is_anonymous and not Activation.objects.filter(activation_key=activation_key).exists():
        logger.info('Password change fail: Wrong activation key')
        return JsonResponse({
            'status': 'fail',
            'message': 'Wrong activation key',
        })

    elif request.user.is_authenticated:
        user = request.user
    else:
        activation_obj = Activation.objects.get(activation_key=activation_key)
        user = activation_obj.user
        activation_obj.delete()

    new_password1 = request.POST.get('new_password1')
    new_password2 = request.POST.get('new_password2')

    if new_password1 != new_password2:
        logger.info('Password change fail: Passwords mismatch')
        return JsonResponse({
            'status': 'fail',
            'message': 'Passwords mismatch',
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
        'message': 'Inner db error',
    })
