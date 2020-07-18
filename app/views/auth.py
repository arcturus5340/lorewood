import django.conf
import django.contrib.auth
import django.contrib.auth.password_validation
import django.core
import django.core.exceptions
import django.core.mail
import django.http
import django.shortcuts

from django.contrib.auth.models import User

import random
import re
import smtplib
import string

from app.models import Activation


def send_message(subject: str, message: str, user: User):
    from_email = django.conf.settings.EMAIL_HOST_USER
    try:
        django.core.mail.send_mail(subject, message, from_email, [user.email])
    except smtplib.SMTPException:
        return django.http.JsonResponse({
            'status': 'fail',
            'message': 'Ошибка при отправке активационного письма',
        })

def activation_key_generator():
    """Returns a string of 40 random characters"""
    return ''.join(random.choice(string.ascii_uppercase + string.digits + string.ascii_lowercase) for _ in range(40))


def login(request: django.http.HttpRequest):
    user_login = request.POST.get('login')
    user_password = request.POST.get('password')
    user = django.contrib.auth.authenticate(username=user_login, password=user_password)

    if user is None:
        return django.http.JsonResponse({
            'status': 'fail',
            'message': 'Authentication error',
        })

    if user.profile.has_2stepverif:
        obj, created = Activation.objects.update_or_create(user=user)
        if obj.is_registration:
            return django.http.JsonResponse({
                'status': 'fail',
                'message': 'User has not verified the account',
            })
        elif obj.is_email_change:
            return django.http.JsonResponse({
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

        return django.http.JsonResponse({
            'status': 'verification_required',
        })

    django.contrib.auth.login(request, user)
    return django.http.JsonResponse({
        'status': 'ok',
    })


def remember(request: django.http.HttpRequest):
    try:
        user = User.objects.get(email=request.POST.get('email'))
    except django.core.exceptions.MultipleObjectsReturned:
        return django.http.JsonResponse({
            'status': 'fail',
            'message': 'Ошибка безопасности. Обратитесь к администратору сайта',
        })
    except django.core.exceptions.ObjectDoesNotExist:
        return django.http.JsonResponse({
            'status': 'fail',
            'message': 'Пользователь с такими данными не зарегестрирован',
        })

    if not user.is_active:
        return django.http.JsonResponse({
            'status': 'fail',
            'message': 'Пользователь не подтвердил свою электронную почту',
        })

    obj, created = Activation.objects.update_or_create(user=user)
    if obj.is_registration:
        return django.http.JsonResponse({
            'status': 'fail',
            'message': 'User has not verified the account',
        })
    elif obj.is_email_change:
        return django.http.JsonResponse({
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

    return django.http.JsonResponse({
        'status': 'ok',
    })


def change_email(request: django.http.HttpRequest):
    user = request.user
    new_email = request.POST.get('email')

    if User.objects.filter(email=new_email).exists():
        return django.http.JsonResponse({
            'status': 'fail',
            'message': 'Такой эмейл уже зарегестрирован',
        })

    obj, created = Activation.objects.update_or_create(user=user)
    if obj.is_registration:
        return django.http.JsonResponse({
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

    return django.http.JsonResponse({
        'status': 'ok',
    })


# TODO: write own validators
def register(request: django.http.HttpRequest):
    username = request.POST.get('username')
    email = request.POST.get('email')
    password = request.POST.get('password1')
    password_copy = request.POST.get('password2')

    if password != password_copy:
        return django.http.JsonResponse({
            'status': 'fail',
            'message': 'Пароли не совпадают',
        })

    elif not re.match(r'^[a-zA-z]+([a-zA-Z0-9]|_|\.)*$', username):
        return django.http.JsonResponse({
            'status': 'fail',
            'message': 'Логин должен начинаться с латинской буквы, а также состоять только из латинских букв, цифр и символов . и _ ',
        })

    elif User.objects.filter(username=username).exists():
        return django.http.JsonResponse({
            'status': 'fail',
            'message': 'Такой логин уже зарегестрирован',
        })

    elif len(username) > 30:
        return django.http.JsonResponse({
            'status': 'fail',
            'message': 'Слишком длинный логин',
        })

    elif User.objects.filter(email=email).exists():
        return django.http.JsonResponse({
            'status': 'fail',
            'message': 'Такой эмейл уже зарегестрирован',
        })

    try:
        django.contrib.auth.password_validation.validate_password(password)
    except django.core.exceptions.ValidationError as err:
        print(django.contrib.auth.password_validation.password_validators_help_texts())
        minimum_length = re.compile(r'This password is too short. It must contain at least [0-9]* characters?\.')
        attribute_similarity = re.compile(r'The password is too similar to the (username|first_name|last_name|email)\.')

        if list(filter(minimum_length.match, err)):
            return django.http.JsonResponse({
                'status': 'fail',
                'message': 'Пароль слишком короткий. Минимальное количество - {} символов'.format(
                    django.contrib.auth.password_validation.MinimumLengthValidator().min_length
                ),
            })
        elif list(filter(attribute_similarity.match, err)):
            return django.http.JsonResponse({
                'status': 'fail',
                'message': 'Пароль схож с Вашими личными данными',
            })
        elif 'This password is too common.' in err:
            return django.http.JsonResponse({
                'status': 'fail',
                'message': 'Пароль слишком предсказуемый',
            })
        elif 'This password is entirely numeric.' in err:
            return django.http.JsonResponse({
                'status': 'fail',
                'message': 'Пароль не может состоять лишь из цифр',
            })

        return django.http.JsonResponse({
            'status': 'fail',
            'message': 'Ошибка при валидации пароля',
        })

    reguser = django.contrib.auth.models.User.objects.create_user(username, email, password)
    activation_key = activation_key_generator()
    Activation.objects.create_user_key(username, activation_key)

    subject = 'Активация аккаунта sharewood.online'
    message = ('Здравствуйте! \n'
               'Вы зарегестирорвались на сайте sharewood.online. Перейдите по ссылке, чтобы активировать '
               'ваш аккаунт: https://sharewood.online/user/{}/activate/{} \n\n'
               'С уважением, команда Sharewood').format(username, activation_key)
    from_email = django.conf.settings.EMAIL_HOST_USER

    try:
        django.core.mail.send_mail(subject, message, from_email, [email])
    except smtplib.SMTPException:
        return django.http.JsonResponse({
            'status': 'fail',
            'message': 'Ошибка при отправке активационного письма',
        })

    reguser.is_active = False
    reguser.save()

    user = django.contrib.auth.authenticate(username=username, password=password)
    if not user:
        return django.http.JsonResponse({
            'status': 'fail',
            'message': '???',
        })

    django.contrib.auth.login(request, user)
    return django.http.JsonResponse({
        'status': 'ok',
    })


def logout(request: django.http.HttpRequest):
    django.contrib.auth.logout(request)
    return django.shortcuts.redirect("/")


def activate_account(request: django.http.HttpRequest, username: str, activation_key: str):
    try:
        if Activation.objects.get(username=username).activation_key == activation_key:
            this_user = User.objects.get(username=username)
            this_user.is_active = True
            this_user.save()

            django.contrib.auth.login(request, this_user, backend='django.contrib.auth.backends.ModelBackend')

            Activation.objects.get(username=username).delete()
            return django.shortcuts.redirect('/')
    except django.core.exceptions.ObjectDoesNotExist:
        pass

    return django.shortcuts.render(request, 'invalid_activation_key.html')


def verificate_login(request: django.http.HttpRequest, username: str, activation_key: str):
    try:
        if Activation.objects.get(username=username).activation_key == activation_key:
            this_user = django.contrib.auth.models.User.objects.get(username=username)

            django.contrib.auth.login(request, this_user, backend='django.contrib.auth.backends.ModelBackend')

            Activation.objects.get(username=username).delete()
            return django.shortcuts.redirect('/')
    except django.core.exceptions.ObjectDoesNotExist:
        pass

    return django.shortcuts.render(request, 'invalid_activation_key.html')
