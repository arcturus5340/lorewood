import django.conf
import django.contrib.auth
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

import app.models
from app.models import UserActivation, UserEmail, UserTwoVerification


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
        })

    if user.profile.two_verif:
        send_verification_email(user.username, user.email)
        return django.http.JsonResponse({
            'status': 'verification_required',
        })

    django.contrib.auth.login(request, user)
    return django.http.JsonResponse({
        'status': 'ok',
    })


def send_verification_email(username: str, email: str):
    """Sends a message with a unique key for passing the second stage of verification"""
    activation_key = activation_key_generator()

    UserTwoVerification.objects.update_or_create(
        username=username,
        defaults={'activation_key': activation_key},
    )

    subject = 'Двойная верификация аккаунта sharewood.online'
    message = ('Здравствуйте! \n'
               'Поступил запрос на вход на сайт sharewood.online. Перейдите по ссылке, чтобы войти в свой аккаунт: '
               'https://sharewood.online/user/{}/verificate/{} \n\n'
               'С уважением, команда Sharewood').format(username, activation_key)
    from_email = django.conf.settings.EMAIL_HOST_USER

    try:
        django.core.mail.send_mail(subject, message, from_email, [email])
    except smtplib.SMTPException:
        pass


def remember(request: django.http.HttpRequest):
    try:
        user = User.objects.get(email=request.POST.get('email'))
        if not user.is_active:
            return django.http.JsonResponse({
                'status': 'fail',
                'message': 'Пользователь не подтвердил свою электронную почту',
            })

        activation_key = activation_key_generator()

        UserActivation.objects.update_or_create(
            username=user.username,
            defaults={'activation_key': activation_key},
        )

        subject = 'Восстановление аккаунта sharewood.online'
        message = ('Здравствуйте!\n'
                   'Перейдите по ссылке, чтобы поменять ваш пароль: '
                   'https://sharewood.online/user/{}/remember/{}\n\n'
                   'С уважением, команда Sharewood').format(user.username, activation_key)
        from_email = django.conf.settings.EMAIL_HOST_USER

        try:
            django.core.mail.send_mail(subject, message, from_email, [user.email])
        except smtplib.SMTPException:
            return django.http.JsonResponse({
                'status': 'fail',
                'message': 'Ошибка при отправке активационного письма',
            })

        return django.http.JsonResponse({
            'status': 'ok',
        })

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


def change_email(request: django.http.HttpRequest):
    username = request.user.username
    new_email = request.POST.get('email')

    if User.objects.filter(email=new_email).exists():
        return django.http.JsonResponse({
            'status': 'fail',
            'message': 'Такой эмейл уже зарегестрирован',
        })

    activation_key = activation_key_generator()
    UserEmail.objects.create(username, activation_key, new_email)

    subject = 'Изменение email аккаунта sharewood.online'
    message = ('Здравствуйте!\n'
               'Перейдите по ссылке, чтобы подтвердить данный email: '
               'https://sharewood.online/user/{}/change-email/\n\n'
               'С уважением, команда Sharewood').format(username, activation_key)
    from_email = django.conf.settings.EMAIL_HOST_USER

    try:
        django.core.mail.send_mail(subject, message, from_email, [new_email])
    except smtplib.SMTPException:
        return django.http.JsonResponse({
            'status': 'fail',
            'message': 'Ошибка при отправке активационного письма',
        })

    return django.http.JsonResponse({
        'status': 'ok',
    })


# TODO: handle secret_keys identity exception
def send_activation_email(username: str, email: str):
    activation_key = activation_key_generator()
    app.models.UserActivation.objects.create_user_key(username, activation_key)

    subject = 'Активация аккаунта sharewood.online'
    message = ('Здравствуйте! \n'
               'Вы зарегестирорвались на сайте sharewood.online. Перейдите по ссылке, чтобы активировать '
               'ваш аккаунт: https://sharewood.online/user/{}/activate/{} \n\n'
               'С уважением, команда Sharewood').format(username, activation_key)

    from_email = django.conf.settings.EMAIL_HOST_USER
    return django.core.mail.send_mail(subject, message, from_email, [email])


def register(request: django.http.HttpRequest):
    username = request.POST.get('username')
    email = request.POST.get('email')
    password1 = request.POST.get('password1')
    password2 = request.POST.get('password2')

    response_data = {}
    if password1 != password2:
        response_data['result'] = 'Пароли не совпадают'
        # logging.warning('failed registration attempt (passwords do not match)')

    elif not re.match(r'^[a-zA-z]+([a-zA-Z0-9]|_|\.)*$', username):
        response_data['result'] = ('Логин должен начинаться с латинской буквы, '
                                   'а также состоять только из латинских букв, цифр и символов . и _ ')
        # logging.warning('failed registration attempt (wrong login format)')

    elif django.contrib.auth.models.User.objects.filter(username=username).exists():
        response_data['result'] = 'Такой юзернейм уже зарегестрирован'
        # logging.warning('failed registration attempt (existing username)')

    elif len(username) > 30:
        response_data['result'] = 'Слишком длинный логин'
        # logging.warning('failed registration attempt (username is too long)')

    elif django.contrib.auth.models.User.objects.filter(email=email).exists():
        response_data['result'] = 'Такой эмейл уже зарегестрирован'
        # logging.warning('failed registration attempt (existing email)')

    else:
        response_data['result'] = validate_password(password1)
        if not response_data['result']:
            reguser = django.contrib.auth.models.User.objects.create_user(username, email, password1)
            if send_activation_email(username=username, email=email):
                reguser.is_active = False
                reguser.save()
                response_data['result'] = 'Success!'
                # logging.info('registration success (username: \'{}\')'.format(username))

                user = django.contrib.auth.authenticate(username=username, password=password1)
                if user:
                    django.contrib.auth.login(request, user)
                    # logging.info('user \'{}\' logged in'.format(username))
                else:
                    response_data['result'] = 'Failed!'
                    # logging.warning('failed login attempt')
            else:
                response_data['result'] = 'Ошибка при отправке письма с активацией'
                # logging.error('failed registration attempt (no email was sent)')

    return django.http.JsonResponse(response_data)


def validate_password(password: str):
    try:
        django.contrib.auth.password_validation.validate_password(password)
    except django.core.exceptions.ValidationError as err:
        minimum_length = re.compile(r'This password is too short. It must contain at least [0-9]* characters?\.')
        attribute_similarity = re.compile(r'The password is too similar to the (username|first_name|last_name|email)\.')

        if list(filter(minimum_length.match, err)):
            return 'Пароль слишком короткий. Минимальное количество - {} символов'.format(
                django.contrib.auth.password_validation.MinimumLengthValidator().min_length
            )
        elif list(filter(attribute_similarity.match, err)):
            return 'Пароль схож с Вашими личными данными'
        elif 'This password is too common.' in err:
            return 'Пароль слишком предсказуемый'
        elif 'This password is entirely numeric.' in err:
            return 'Пароль не может состоять лишь из цифр'
        return 'Ошибка при валидации пароля'
    return


def logout(request: django.http.HttpRequest):
    django.contrib.auth.logout(request)
    # logging.info('user \'{}\' logged out'.format(request.user.username))
    return django.shortcuts.redirect("/")

def activate_account(request: django.http.HttpRequest, username: str, activation_key: str):
    try:
        if app.models.UserActivation.objects.get(username=username).activation_key == activation_key:
            this_user = django.contrib.auth.models.User.objects.get(username=username)
            this_user.is_active = True
            # logging.info('user \'{}\' is activated'.format(username))
            this_user.save()

            django.contrib.auth.login(request, this_user, backend='django.contrib.auth.backends.ModelBackend')
            # logging.info('user \'{}\' logged in'.format(username))

            app.models.UserActivation.objects.get(username=username).delete()
            return django.shortcuts.redirect('/')
        # logging.warning('failed account activation attempt (keys do not match)')
    except django.core.exceptions.ObjectDoesNotExist:
        pass
        # logging.error('failed account activation attempt (user \'{}\' does not exist)'.format(username))

    template = django.template.loader.get_template('../templates/invalid_activation_key.html')
    return django.http.HttpResponse(template.render())


def verificate_login(request: django.http.HttpRequest, username: str, activation_key: str):
    try:
        if app.models.UserTwoVerification.objects.get(username=username).activation_key == activation_key:
            this_user = django.contrib.auth.models.User.objects.get(username=username)
            # logging.info('user \'{}\' is verificated'.format(username))

            django.contrib.auth.login(request, this_user, backend='django.contrib.auth.backends.ModelBackend')
            # logging.info('user \'{}\' logged in'.format(username))

            app.models.UserTwoVerification.objects.get(username=username).delete()
            return django.shortcuts.redirect('/')
        # logging.warning('failed account verification attempt (keys do not match)')
    except django.core.exceptions.ObjectDoesNotExist:
        pass
        # logging.error('failed account verification attempt (user \'{}\' does not exist)'.format(username))

    template = django.template.loader.get_template('../templates/invalid_activation_key.html')
    return django.http.HttpResponse(template.render())
