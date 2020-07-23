from django.conf import settings
from django.contrib import auth
from django.contrib.auth import password_validation
from django.contrib.auth.models import User
from django.core import mail
from django.core import exceptions
from django.http.request import HttpRequest
from django.http.response import JsonResponse
from django.shortcuts import redirect
from django.utils.translation import gettext as _

import logging
import random
import re
import smtplib
import string

from app.models import ActivationKey, Profile

logger = logging.getLogger('app')


def is_verified(func):
    def wrapper(request: HttpRequest, *args, **kwargs):
        if request.user.profile.is_verified:
            return func(request, *args, **kwargs)
        else:
            return JsonResponse({
                'status': 'fail',
                'message': _('Verify your account first'),
            })
    return wrapper


def send_message(subject: str, message: str, user: User):
    from_email = settings.EMAIL_HOST_USER
    try:
        mail.send_mail(subject, message, from_email, [user.email])
    except smtplib.SMTPException as err:
        logger.warning('SMTP error: {}'.format(err.args[1]))
        return JsonResponse({
            'status': 'fail',
            'message': _('Error sending activation email'),
        })


def activation_key_generator():
    """Returns a string of 40 random characters"""
    return ''.join(random.choice(string.ascii_uppercase + string.digits + string.ascii_lowercase) for _ in range(40))


def login(request: HttpRequest):
    user_login = request.POST.get('login')
    user_password = request.POST.get('password')
    user = auth.authenticate(username=user_login, password=user_password)

    if user is None:
        logger.info('Authentication fail: Invalid credentials')
        return JsonResponse({
            'status': 'fail',
            'message': _('Invalid credentials'),
        })

    if user.profile.has_2step_verification:
        obj, created = ActivationKey.objects.get_or_create(user=user)
        obj.activation_key = activation_key_generator()
        obj.is_2step_verification = True
        obj.save()

        subject = 'Двойная верификация аккаунта lorewood.online'
        message = ('Здравствуйте! \n'
                   'Поступил запрос на вход на сайт lorewood.online. Перейдите по ссылке, чтобы войти в свой аккаунт: '
                   'https://lorewood.online/user/{}/verify/{} \n\n'
                   'С уважением, команда Lorewood').format(user.username, obj.activation_key)

        send_message(subject, message, user)

        return JsonResponse({
            'status': 'ok',
        })

    auth.login(request, user)
    return JsonResponse({
        'status': 'ok',
    })


@is_verified
def change_email(request: HttpRequest):
    user = request.user
    new_email = request.POST.get('email')

    if User.objects.filter(email=new_email).exists():
        logger.info('Email change fail: Attempting to register an existing email')
        return JsonResponse({
            'status': 'fail',
            'message': _('This email address is already registered'),
        })

    obj, created = ActivationKey.objects.update_or_create(user=user)
    obj.activation_key = activation_key_generator()
    obj.new_email = new_email
    obj.is_email_change = True
    obj.save()

    subject = 'Изменение email аккаунта lorewood.online'
    message = ('Здравствуйте!\n'
               'Перейдите по ссылке, чтобы подтвердить данный email: '
               'https://lorewood.online/user/{}/change-email/\n\n'
               'С уважением, команда Lorewood').format(user.username, obj.activation_key)

    send_message(subject, message, user)

    return JsonResponse({
        'status': 'ok',
    })


def register(request: HttpRequest):
    from django.utils.translation import gettext as _

    username = request.POST.get('username')
    email = request.POST.get('email')
    password = request.POST.get('password1')
    password_copy = request.POST.get('password2')

    if password != password_copy:
        logger.info('Registration fail: Passwords mismatch')
        return JsonResponse({
            'status': 'fail',
            'message': _('Passwords mismatch'),
        })

    elif not re.match(r'^([a-zA-Z0-9]|_|\.)*$', username):
        logger.info('Registration fail: Invalid login format')
        return JsonResponse({
            'status': 'fail',
            'message': _('Username can contain only letters, numbers, and the \'_\' and \'.\' character'),
        })

    elif User.objects.filter(username=username).exists():
        logger.info('Registration fail: Attempting to register an existing username')
        return JsonResponse({
            'status': 'fail',
            'message': _('This username is already registered'),
        })

    elif len(username) > 32:
        logger.info('Registration fail: Username is too long')
        return JsonResponse({
            'status': 'fail',
            'message': _('Username is too long'),
        })

    elif User.objects.filter(email=email).exists():
        logger.info('Registration fail: Attempting to register an existing email')
        return JsonResponse({
            'status': 'fail',
            'message': _('This email address is already registered'),
        })

    try:
        auth.password_validation.validate_password(password)
    except exceptions.ValidationError as err:
        # TODO: Log on English
        logger.info('Registration fail: {}'.format(err.messages[0]))
        return JsonResponse({
            'status': 'fail',
            'message': err.messages[0],
        })

    user = auth.models.User.objects.create_user(username, email, password)
    Profile.objects.create(user=user)
    obj, _ = ActivationKey.objects.update_or_create(
        user=user,
        defaults={'is_registration': True, 'activation_key': activation_key_generator()},
    )

    subject = 'Активация аккаунта lorewood.online'
    message = ('Здравствуйте! \n'
               'Вы зарегестирорвались на сайте lorewood.online. Перейдите по ссылке, чтобы активировать '
               'ваш аккаунт: https://lorewood.online/user/{}/activate/{} \n\n'
               'С уважением, команда Lorewood').format(username, obj.activation_key)

    send_message(subject, message, user)

    user = auth.authenticate(username=username, password=password)
    if not user:
        return JsonResponse({
            'status': 'fail',
            'message': _('Authentication error'),
        })

    auth.login(request, user)

    return JsonResponse({
        'status': 'ok',
    })


def remember(request: HttpRequest):
    try:
        email = request.POST.get('email')
        user = User.objects.get(email=email)
    except exceptions.MultipleObjectsReturned:
        logger.warning('DataBase error: Multiple users are returned for one email address')
        return JsonResponse({
            'status': 'fail',
            'message': _('Security error. Contact site administrator'),
        })
    except exceptions.ObjectDoesNotExist:
        logger.warning('Password recovery fail: No user with given email address was found')
        return JsonResponse({
            'status': 'fail',
            'message': _('User with this email address is not registered'),
        })

    obj, created = ActivationKey.objects.update_or_create(user=user)
    obj.activation_key = activation_key_generator()
    obj.is_remember = True
    obj.save()

    subject = 'Восстановление аккаунта lorewood.online'
    message = ('Здравствуйте!\n'
               'Перейдите по ссылке, чтобы поменять ваш пароль: '
               'https://lorewood.online/user/{}/remember/{}\n\n'
               'С уважением, команда Lorewood').format(user.username, obj.activation_key)

    send_message(subject, message, user)

    return JsonResponse({
        'status': 'ok',
    })


def logout(request: HttpRequest):
    auth.logout(request)
    return redirect("/")
