import django.conf
import django.contrib.auth
import django.contrib.auth.forms
import django.contrib.auth.models
import django.contrib.auth.password_validation
import django.core.exceptions
import django.core.mail
import django.core.paginator
import django.db.models
import django.db.utils
import django.http
import django.shortcuts
import django.template.loader
import django.views.decorators.csrf

import datetime
import logging
import PIL.Image

import app.forms
import app.models


def cabinet(request: django.http.HttpRequest, username: str, section: str):
    if request.user.username == username:
        return django.shortcuts.render(request, 'user/cabinet.html', {'premium': 1000, 'section': section})
    return django.shortcuts.redirect('/')


def save_personal_data(request: django.http.HttpRequest):
    user = request.user
    try:
        im = PIL.Image.open(request.FILES.get('avatar'))
        width, height = im.size
        new_size = min(height, width)
        left = (width - new_size) / 2
        top = (height - new_size) / 2
        right = (width + new_size) / 2
        bottom = (height + new_size) / 2

        filename = django.conf.settings.MEDIA_ROOT + '/avatars/cropped/cropped-{}crop.jpg'.format(user.username)

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

    return django.shortcuts.redirect("/user/{}/cabinet/default".format(user.username))


def change_email_confirm(request: django.http.HttpRequest, username, activation_key):
    try:
        compare_data = app.models.UserEmail.objects.get(username=username)
        if compare_data.activation_key != activation_key:
            logging.warning('failed account activation attempt (keys do not match)')
            template = django.template.loader.get_template('../templates/invalid_activation_key.html')
            return django.http.HttpResponse(template.render())

        user = django.contrib.auth.models.User.objects.get(username=username)
        user.email = compare_data.email
        user.is_active = True
        user.save()
        logging.info('email change confirmed (username: {})'.format(username))

        django.contrib.auth.login(request, user, backend='django.contrib.auth.backends.ModelBackend')
        logging.info('user \'{}\' logged in'.format(username))
        app.models.UserEmail.objects.get(username=username).delete()

    except django.core.exceptions.ObjectDoesNotExist:
        logging.error('failed password recovery attempt (user \'{}\' does not exist)'.format(username))

    return django.shortcuts.redirect(request, '/user/profile.html')


def two_verif_on(request):
    message = "SUCCESS"
    if request.user.is_active:
        request.user.profile.two_verif = True
        request.user.save()
    else:
        message = "ACTIVATE"

    return django.shortcuts.redirect('/user/{}/cabinet/list-settings?message={}'.format(request.user.username, message))

def two_verif_off(request):
    request.user.profile.two_verif = False
    request.user.save()

    return django.shortcuts.redirect('/user/{}/cabinet/list-settings'.format(request.user.username))


# TODO: double buy
# TODO: is user active
def buy_premium(request):
    message = 0

    if request.user.is_active:
        cost = 1000
        balance = request.user.profile.balance
        new_balance = balance - cost
        if new_balance < 0:
            message = "NOT_ENOUGH"
        else :
            request.user.profile.balance = new_balance
            request.user.save()

            request.user.profile.is_premium = True
            request.user.save()

            today = datetime.date.today() + datetime.timedelta(days=1)
            try:
                obj = app.models.Revenue.objects.get(date=today)
                obj.income += cost
                obj.save()
            except django.core.exceptions.ObjectDoesNotExist:
                app.models.Revenue.objects.create(date=today, income=cost)

    else:
        message = "ACTIVATE"

    return django.shortcuts.redirect('/user/{}/cabinet/list-buy-premium'.format(request.user.username))


def change_password(request: django.http.HttpRequest, username: str):
    new_password1 = request.POST.get('new_password1')
    new_password2 = request.POST.get('new_password2')
    response_data = {}

    if new_password1 != new_password2:
        response_data['result'] = 'Пароли не совпадают'
        # logging.warning('failed password change attempt (passwords do not match)')
    else:
        response_data['result'] = django.contrib.auth.password_validation.validate_password(new_password1)
        if not response_data['result']:
            try:
                user = django.contrib.auth.models.User.objects.get(username=username)
                user.set_password(new_password1)
                user.save()
            except django.core.exceptions.ObjectDoesNotExist:
                pass
                # logging.error('failed password change attempt (user \'{}\' does not exist)'.format(username))

            django.contrib.auth.login(request, user, backend='django.contrib.auth.backends.ModelBackend')
            # logging.info('user \'{}\' logged in'.format(username))
            # @abinba Are you sure, that you need to delete record from UserActivation?
            app.models.UserActivation.objects.get(username=username).delete()
            response_data['result'] = 'Success!'
            # logging.info('password changed (username: \'{}\')'.format(username))

    return django.http.JsonResponse(response_data)


def change_cabinet_password(request: django.http.HttpRequest):
    username = request.user.username
    new_password1 = request.POST.get('new_password1')
    new_password2 = request.POST.get('new_password2')
    response_data = {}

    if new_password1 != new_password2:
        response_data['result'] = 'Пароли не совпадают'
        # logging.warning('failed cabinet password change attempt (passwords do not match)')
    else:
        response_data['result'] = django.contrib.auth.password_validation.validate_password(new_password1)
        if not response_data['result']:
            try:
                user = django.contrib.auth.models.User.objects.get(username=username)
                user.set_password(new_password1)
                user.save()
            except django.core.exceptions.ObjectDoesNotExist:
                pass
                # logging.error('failed cabinet password change attempt (user \'{}\' does not exist)'.format(username))

            django.contrib.auth.login(request, user, backend='django.contrib.auth.backends.ModelBackend')
            # logging.info('user \'{}\' logged in'.format(username))
            response_data['result'] = 'Success!'
            # logging.info('cabinet password changed (username: \'{}\')'.format(username))

    return django.http.JsonResponse(response_data)
