from django.conf import settings
from django.contrib import auth
from django.contrib.auth import password_validation
from django.contrib.auth.models import User
from django.core import mail
from django.core import exceptions
from django.http.request import HttpRequest
from django.http.response import JsonResponse
from django.shortcuts import redirect, render

import random
import re
import smtplib
import string

from app.models import Activation


def send_message(subject: str, message: str, user: User):
    from_email = settings.EMAIL_HOST_USER
    try:
        mail.send_mail(subject, message, from_email, [user.email])
    except smtplib.SMTPException:
        return JsonResponse({
            'status': 'fail',
            'message': 'Ошибка при отправке активационного письма',
        })


def activation_key_generator():
    """Returns a string of 40 random characters"""
    return ''.join(random.choice(string.ascii_uppercase + string.digits + string.ascii_lowercase) for _ in range(40))


def login(request: HttpRequest):
    user_login = request.POST.get('login')
    user_password = request.POST.get('password')
    user = auth.authenticate(username=user_login, password=user_password)

    if user is None:
        return JsonResponse({
            'status': 'fail',
            'message': 'Authentication error',
        })

    if user.profile.has_2stepverif:
        obj, created = Activation.objects.update_or_create(user=user)
        if obj.is_registration:
            return JsonResponse({
                'status': 'fail',
                'message': 'User has not verified the account',
            })
        elif obj.is_email_change:
            return JsonResponse({
                'status': 'fail',
                'message': 'User has not verified the new email address',
            })

        obj.activation_key = activation_key_generator()
        obj.is_2stepverif = True
        obj.save()

        subject = 'Двойная верификация аккаунта sharewood.online'
        message = ('Здравствуйте! \n'
                   'Поступил запрос на вход на сайт sharewood.online. Перейдите по ссылке, чтобы войти в свой аккаунт: '
                   'https://sharewood.online/user/{}/verificate/{} \n\n'
                   'С уважением, команда Sharewood').format(user.username, obj.activation_key)

        send_message(subject, message, user)

        return JsonResponse({
            'status': 'verification_required',
        })

    auth.login(request, user)
    return JsonResponse({
        'status': 'ok',
    })


def remember(request: HttpRequest):
    try:
        user = User.objects.get(email=request.POST.get('email'))
    except exceptions.MultipleObjectsReturned:
        return JsonResponse({
            'status': 'fail',
            'message': 'Ошибка безопасности. Обратитесь к администратору сайта',
        })
    except exceptions.ObjectDoesNotExist:
        return JsonResponse({
            'status': 'fail',
            'message': 'Пользователь с такими данными не зарегестрирован',
        })

    if not user.profile.is_verified:
        return JsonResponse({
            'status': 'fail',
            'message': 'Пользователь не подтвердил свою электронную почту',
        })

    obj, created = Activation.objects.update_or_create(user=user)
    if obj.is_registration:
        return JsonResponse({
            'status': 'fail',
            'message': 'User has not verified the account',
        })
    elif obj.is_email_change:
        return JsonResponse({
            'status': 'fail',
            'message': 'User has not verified the new email address',
        })

    obj.activation_key = activation_key_generator()
    obj.is_remember = True
    obj.save()

    subject = 'Восстановление аккаунта sharewood.online'
    message = ('Здравствуйте!\n'
               'Перейдите по ссылке, чтобы поменять ваш пароль: '
               'https://sharewood.online/user/{}/remember/{}\n\n'
               'С уважением, команда Sharewood').format(user.username, obj.activation_key)

    send_message(subject, message, user)

    return JsonResponse({
        'status': 'ok',
    })


def change_email(request: HttpRequest):
    user = request.user
    new_email = request.POST.get('email')

    if User.objects.filter(email=new_email).exists():
        return JsonResponse({
            'status': 'fail',
            'message': 'Такой эмейл уже зарегестрирован',
        })

    obj, created = Activation.objects.update_or_create(user=user)
    if obj.is_registration:
        return JsonResponse({
            'status': 'fail',
            'message': 'User has not verified the account',
        })

    obj.activation_key = activation_key_generator()
    obj.new_email = new_email
    obj.is_email_change = True
    obj.save()

    subject = 'Изменение email аккаунта sharewood.online'
    message = ('Здравствуйте!\n'
               'Перейдите по ссылке, чтобы подтвердить данный email: '
               'https://sharewood.online/user/{}/change-email/\n\n'
               'С уважением, команда Sharewood').format(user.username, obj.activation_key)

    send_message(subject, message, user)

    return JsonResponse({
        'status': 'ok',
    })


def register(request: HttpRequest):
    username = request.POST.get('username')
    email = request.POST.get('email')
    password = request.POST.get('password1')
    password_copy = request.POST.get('password2')

    if password != password_copy:
        return JsonResponse({
            'status': 'fail',
            'message': 'Пароли не совпадают',
        })

    elif not re.match(r'^[a-zA-z]+([a-zA-Z0-9]|_|\.)*$', username):
        return JsonResponse({
            'status': 'fail',
            'message': 'Логин должен начинаться с латинской буквы, '
                       'а также состоять только из латинских букв, цифр и символов . и _ ',
        })

    elif User.objects.filter(username=username).exists():
        return JsonResponse({
            'status': 'fail',
            'message': 'Такой логин уже зарегестрирован',
        })

    elif len(username) > 32:
        return JsonResponse({
            'status': 'fail',
            'message': 'Слишком длинный логин',
        })

    elif User.objects.filter(email=email).exists():
        return JsonResponse({
            'status': 'fail',
            'message': 'Такой эмейл уже зарегестрирован',
        })

    try:
        auth.password_validation.validate_password(password)
    except exceptions.ValidationError as err:
        return JsonResponse({
            'status': 'fail',
            'message': err.messages[0],
        })

    user = auth.models.User.objects.create_user(username, email, password)
    obj, _ = Activation.objects.update_or_create(
        user=user,
        defaults={'is_registration': True, 'activation_key': activation_key_generator()},
    )

    subject = 'Активация аккаунта sharewood.online'
    message = ('Здравствуйте! \n'
               'Вы зарегестирорвались на сайте sharewood.online. Перейдите по ссылке, чтобы активировать '
               'ваш аккаунт: https://sharewood.online/user/{}/activate/{} \n\n'
               'С уважением, команда Sharewood').format(username, obj.activation_key)

    send_message(subject, message, user)

    user = auth.authenticate(username=username, password=password)
    if not user:
        return JsonResponse({
            'status': 'fail',
            'message': 'Authentication error',
        })

    auth.login(request, user)
    return JsonResponse({
        'status': 'ok',
    })


def logout(request: HttpRequest):
    auth.logout(request)
    return redirect("/")


def activate_account(request: HttpRequest, username: str, activation_key: str):
    try:
        activation_obj = Activation.objects.get(username=username)
        if (activation_obj.activation_key == activation_key) and activation_obj.is_registration:
            user = User.objects.get(username=username)
            user.profile.is_verified = True
            user.save()
            auth.login(request, user, backend='django.contrib.auth.backends.ModelBackend')
            activation_obj.delete()
            return redirect('/')

    except exceptions.ObjectDoesNotExist:
        pass

    return render(request, 'invalid_activation_key.html')


def verificate_login(request: HttpRequest, username: str, activation_key: str):
    try:
        activation_obj = Activation.objects.get(username=username)
        if (activation_obj.activation_key == activation_key) and activation_obj.is_2stepverif:
            user = User.objects.get(username=username)
            auth.login(request, user, backend='django.contrib.auth.backends.ModelBackend')
            activation_obj.delete()
            return redirect('/')

    except exceptions.ObjectDoesNotExist:
        pass

    return render(request, 'invalid_activation_key.html')


def password_change(request: HttpRequest, username: str, activation_key: str):
    try:
        activation_obj = Activation.objects.get(username=username)
        if (activation_obj.activation_key == activation_key) and activation_obj.is_remember:
            user = User.objects.get(username=username)
            auth.login(request, user, backend='django.contrib.auth.backends.ModelBackend')
            activation_obj.delete()
            return redirect('/')

    except exceptions.ObjectDoesNotExist:
        pass

    return render(request, 'invalid_activation_key.html')


def change_email_confirm(request: HttpRequest, username, activation_key):
    try:
        activation_obj = Activation.objects.get(username=username)
        if (activation_obj.activation_key == activation_key) and activation_obj.is_email_change:
            user = User.objects.get(username=username)
            auth.login(request, user, backend='django.contrib.auth.backends.ModelBackend')
            activation_obj.delete()
            return redirect('/')

    except exceptions.ObjectDoesNotExist:
        pass

    return render(request, 'invalid_activation_key.html')
