from django.conf import settings
from django.http.request import HttpRequest
from django.http.response import HttpResponse, JsonResponse
from django.shortcuts import redirect, render
from django.contrib.auth.models import User

from django.core import exceptions
from django.contrib import auth

import datetime
import PIL.Image

import app.forms
from app.models import Activation, Global_Settings, Revenue


def cabinet(request: HttpRequest, username: str, section: str):
    if request.user.username == username:
        return render(request, 'user/cabinet.html', {'premium': Global_Settings.objects.get(setting='Premium').value, 'section': section})
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
        pass
    except KeyError:
        pass
    except AttributeError:
        pass

    return redirect("/user/{}/cabinet/default".format(user.username))


def two_verif_on(request: HttpRequest):
    response = dict()
    user = request.user
    if Activation.objects.filter(user=user, is_registration=True).exists:
        user.profile.has_2stepverif = True
        user.save()
        response['status'] = 'ok'
    else:
        response['status'] = 'fail'
        response['message'] = 'Verification required'

    return JsonResponse(response)


def two_verif_off(request):
    user = request.user
    user.profile.two_verif = False
    user.save()
    return redirect('/user/{}/cabinet/list-settings'.format(request.user.username))


def buy_premium(request):
    user = request.user

    if not user.profile.is_verified:
        JsonResponse({
            'status': 'fail',
            'message': 'User is not verified',
        })

    if user.profile.is_premium:
        JsonResponse({
            'status': 'fail',
            'message': 'User have premium',
        })

    cost = Global_Settings.objects.get(setting='Premium').value
    if user.profile.balance - cost < 0:
        JsonResponse({
            'status': 'fail',
            'message': 'Balance is not enought',
        })

    user.profile.balance -= cost
    user.profile.is_premium = True
    user.save()

    obj, _ = Revenue.objects.get_or_create(date=datetime.date.today(), defaults={'income': 0})
    obj.income += cost
    obj.save()

    return redirect('/user/{}/cabinet/list-buy-premium'.format(request.user.username))


def change_password(request: HttpRequest, username: str):
    new_password1 = request.POST.get('new_password1')
    new_password2 = request.POST.get('new_password2')
    response_data = {}

    if new_password1 != new_password2:
        response_data['result'] = 'Пароли не совпадают'
    else:
        response_data['result'] = auth.password_validation.validate_password(new_password1)
        if not response_data['result']:
            try:
                user = auth.models.User.objects.get(username=username)
                user.set_password(new_password1)
                user.save()
            except exceptions.ObjectDoesNotExist:
                pass

            auth.login(request, user, backend='django.contrib.auth.backends.ModelBackend')
            # ???
            Activation.objects.get(username=username).delete()
            response_data['result'] = 'Success!'

    return JsonResponse(response_data)

