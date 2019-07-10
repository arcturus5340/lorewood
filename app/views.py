# -*- coding: utf-8 -*-

import django.conf
import django.core.exceptions
import django.core.mail
import django.core.paginator
import django.contrib.auth
import django.contrib.auth.forms
import django.contrib.auth.models
import django.contrib.auth.password_validation
import django.db.models
import django.db.utils
import django.http
import django.shortcuts
import django.template.loader
import django_registration.backends.activation.views
import django_registration.forms
import django.views.decorators.csrf
import el_pagination.decorators

import datetime
import json
import logging
import os
import PIL.Image
import random
import re
import string
import typing
import filetype

import app.forms
import app.models
import app.views_api

address = 'https://sharewood.online/'
logging.getLogger(__name__)
logging.basicConfig(format=u'[%(asctime)s] %(levelname)-8s: %(message)s',
                    filename=os.path.join(django.conf.settings.BASE_DIR, 'sharewood.log'), level=logging.NOTSET)


@el_pagination.decorators.page_template('records_list.html')
def index(request: django.http.HttpRequest, template: str = 'index.html', extra_context: typing.Optional[dict] = None):
    regform = django_registration.forms.RegistrationForm
    authform = django.contrib.auth.forms.AuthenticationForm
    authnext = "/"

    records = list(app.models.Records.objects.all())
    last_records = records[-4:]

    context = {
        'regform': regform,
        'form': authform,
        'next': authnext,
        'records': records,
        'last_records': last_records,
    }

    if extra_context is not None:
        context.update(extra_context)

    return django.shortcuts.render(request, template, context)


def login(request: django.http.HttpRequest):
    user_login = request.POST.get('login')
    user_password = request.POST.get('password')
    user = django.contrib.auth.authenticate(username=user_login, password=user_password)

    response_data = {}
    if user:
        django.contrib.auth.login(request, user)
        response_data['result'] = 'Success!'
        logging.info('user \'{}\' logged in'.format(user_login))
    else:
        response_data['result'] = 'Failed!'
        logging.warning('failed login attempt')

    return django.http.HttpResponse(json.dumps(response_data), content_type="application/json")


def logout(request: django.http.HttpRequest):
    user_login = request.user.username
    django.contrib.auth.logout(request)
    logging.info('user \'{}\' logged out'.format(user_login))
    return django.shortcuts.redirect("/")


def register(request: django.http.HttpRequest):
    username = request.POST.get('username')
    email = request.POST.get('email')
    password1 = request.POST.get('password1')
    password2 = request.POST.get('password2')

    response_data = {}
    if password1 != password2:
        response_data['result'] = 'Пароли не совпадают'
        logging.warning('failed registration attempt (passwords do not match)')

    elif not re.match(r'^[a-zA-z]+([a-zA-Z0-9]|_|\.)*$', username):
        response_data['result'] = ('Логин должен начинаться с латинской буквы, '
                                   'а также состоять только из латинских букв, цифр и символов . и _ ')
        logging.warning('failed registration attempt (wrong login format)')

    elif django.contrib.auth.models.User.objects.filter(username=username).exists():
        response_data['result'] = 'Такой юзернейм уже зарегестрирован'
        logging.warning('failed registration attempt (existing username)')

    elif len(username) > 30:
        response_data['result'] = 'Слишком длинный логин'
        logging.warning('failed registration attempt (username is too long)')

    elif django.contrib.auth.models.User.objects.filter(email=email).exists():
        response_data['result'] = 'Такой эмейл уже зарегестрирован'
        logging.warning('failed registration attempt (existing email)')

    else:
        response_data['result'] = validate_password(password1)
        if not response_data['result']:
            reguser = django.contrib.auth.models.User.objects.create_user(username, email, password1)
            if send_activation_email(username=username, email=email):
                reguser.is_active = False
                reguser.save()
                response_data['result'] = 'Success!'
                logging.info('registration success (username: \'{}\')'.format(username))

                user = django.contrib.auth.authenticate(username=username, password=password1)
                if user:
                    django.contrib.auth.login(request, user)
                    logging.info('user \'{}\' logged in'.format(username))
                else:
                    response_data['result'] = 'Failed!'
                    logging.warning('failed login attempt')
            else:
                response_data['result'] = 'Ошибка при отправке письма с активацией'
                logging.error('failed registration attempt (no email was sent)')

    return django.http.HttpResponse(json.dumps(response_data), content_type='application/json')


def advertising(request: django.http.HttpRequest):
    return django.shortcuts.render(request, 'advertising.html')


def donations(request: django.http.HttpRequest):
    return django.shortcuts.render(request, 'donations.html')


def info(request: django.http.HttpRequest):
    return django.shortcuts.render(request, 'info.html')


def regulations(request: django.http.HttpRequest):
    return django.shortcuts.render(request, 'regulations.html')


def rightholder(request: django.http.HttpRequest):
    return django.shortcuts.render(request, 'rightholder.html')


def activate_account(request: django.http.HttpRequest, username: str, activation_key: str):
    try:
        if app.models.UserActivation.objects.get(username=username).activation_key == activation_key:
            this_user = django.contrib.auth.models.User.objects.get(username=username)
            this_user.is_active = True
            logging.info('user \'{}\' is activated'.format(username))
            this_user.save()

            django.contrib.auth.login(request, this_user, backend='django.contrib.auth.backends.ModelBackend')
            logging.info('user \'{}\' logged in'.format(username))

            app.models.UserActivation.objects.get(username=username).delete()
            return django.shortcuts.redirect('/')
        logging.warning('failed account activation attempt (keys do not match)')
    except django.core.exceptions.ObjectDoesNotExist:
        logging.error('failed account activation attempt (user \'{}\' does not exist)'.format(username))

    template = django.template.loader.get_template('../templates/invalid_activation_key.html')
    return django.http.HttpResponse(template.render())


#TODO: refucktor this plz, @abinba
def save_personal_data(request: django.http.HttpRequest):
    username = request.user.username
    filename = "default"

    try:
        im = PIL.Image.open(request.FILES.get('avatar'))
        width, height = im.size

        filename = django.conf.settings.MEDIA_ROOT + '/avatars/cropped/cropped-{}crop.jpg'.format(username)

        if width > height:
            diff = width - height
            new_width = width - diff
            new_height = height

            left = int(diff / 2)
            right = int(new_width + diff / 2)
            top = 0
            bottom = int(new_height)
        elif height > width:
            diff = height - width
            new_height = height - diff
            new_width = width

            left = 0
            right = int(new_width)
            top = int(diff / 2)
            bottom = int(new_height + diff / 2)
        else:
            left = 0
            top = 0
            right = width
            bottom = height

        image = im.crop((left, top, right, bottom))
        image.save(filename)

    except IOError:
        logging.error('image-file could not be open/written')
    except KeyError:
        logging.error('output format could not be determined from the file name')

    user = django.contrib.auth.models.User.objects.get(username=username)
    user.first_name = request.POST.get('first_name')
    user.last_name = request.POST.get('last_name')
    user.profile.bio = request.POST.get('bio')

    if filename != "default":
        user.profile.avatar = '/media/avatars/cropped/cropped-{}crop.jpg'.format(username)
    user.save()

    return django.shortcuts.redirect("/user/{}/cabinet".format(username))


def cabinet(request: django.http.HttpRequest, username: str):
    if request.user.username == username:
        premium = app.models.Premium.objects.get(id=1)
        return django.shortcuts.render(request, 'user/cabinet.html', {'premium' : premium.premium_cost })
    return django.shortcuts.redirect('/')


def password_change_view(request: django.http.HttpRequest, username: str, activation_key: str):
    try:
        if app.models.UserActivation.objects.get(username=username).activation_key != activation_key:
            logging.warning('failed password change attempt (keys do not match)')
            template = django.template.loader.get_template('../templates/invalid_activation_key.html')
            return django.http.HttpResponse(template.render())
    except django.core.exceptions.ObjectDoesNotExist:
        logging.error('failed password change attempt (user \'{}\' does not exist)'.format(username))

    return django.shortcuts.render(request, 'user/password_change.html', {'username': username})


def change_password(request: django.http.HttpRequest, username: str):
    new_password1 = request.POST.get('new_password1')
    new_password2 = request.POST.get('new_password2')
    response_data = {}

    if new_password1 != new_password2:
        response_data['result'] = 'Пароли не совпадают'
        logging.warning('failed password change attempt (passwords do not match)')
    else:
        response_data['result'] = validate_password(new_password1)
        if not response_data['result']:
            try:
                user = django.contrib.auth.models.User.objects.get(username=username)
                user.set_password(new_password1)
                user.save()
            except django.core.exceptions.ObjectDoesNotExist:
                logging.error('failed password change attempt (user \'{}\' does not exist)'.format(username))

            django.contrib.auth.login(request, user, backend='django.contrib.auth.backends.ModelBackend')
            logging.info('user \'{}\' logged in'.format(username))
            # @abinba Are you sure, that you need to delete record from UserActivation?
            app.models.UserActivation.objects.get(username=username).delete()
            response_data['result'] = 'Success!'
            logging.info('password changed (username: \'{}\')'.format(username))

    return django.http.HttpResponse(json.dumps(response_data), content_type='application/json')


def change_cabinet_password(request: django.http.HttpRequest):
    username = request.user.username
    new_password1 = request.POST.get('new_password1')
    new_password2 = request.POST.get('new_password2')
    response_data = {}

    if new_password1 != new_password2:
        response_data['result'] = 'Пароли не совпадают'
        logging.warning('failed cabinet password change attempt (passwords do not match)')
    else:
        response_data['result'] = validate_password(new_password1)
        if not response_data['result']:
            try:
                user = django.contrib.auth.models.User.objects.get(username=username)
                user.set_password(new_password1)
                user.save()
            except django.core.exceptions.ObjectDoesNotExist:
                logging.error('failed cabinet password change attempt (user \'{}\' does not exist)'.format(username))

            django.contrib.auth.login(request, user, backend='django.contrib.auth.backends.ModelBackend')
            logging.info('user \'{}\' logged in'.format(username))
            response_data['result'] = 'Success!'
            logging.info('cabinet password changed (username: \'{}\')'.format(username))

    return django.http.HttpResponse(json.dumps(response_data), content_type='application/json')


def remember(request: django.http.HttpRequest):
    user_login = request.POST.get('user_login')
    response_data = {}

    try:
        this_user = django.contrib.auth.models.User.objects.get(username=user_login)
        if this_user.is_active:
            activation_key = activation_key_generator()
            app.models.UserActivation.objects.create_user_key(this_user.username, activation_key)

            subject = 'Восстановление аккаунта sharewood.online'
            message = ('Здравствуйте!\n'
                       'Перейдите по ссылке, чтобы поменять ваш пароль: {}user/{}/remember/{}\n\n'
                       'С уважением, команда Sharewood').format(address, this_user.username, activation_key)

            from_email = django.conf.settings.EMAIL_HOST_USER
            send_email = django.core.mail.send_mail(subject, message, from_email, [this_user.email])

            if send_email:
                response_data['result'] = 'Success!'
                logging.info('password recovered (username: \'{}\')'.format(user_login))
            else:
                response_data['result'] = 'Ошибка при отправке активационного письма'
                logging.error('failed password recovery attempt (error while sending a letter)')
        else:
            response_data['result'] = 'Пользователь не подтвердил свою электронную почту'
            logging.warning('failed password recovery attempt (user \'{}\' account is not activated)'.format(user_login))
    except django.core.exceptions.MultipleObjectsReturned:
        response_data['result'] = ('Ошибка безопасности. '
                                   'Зарегистрирован еще один пользователь с такими данными. '
                                   'Обратитесь к администратору сайта')
        logging.error('failed password recovery attempt (there are two identical users in the system)')
    except django.core.exceptions.ObjectDoesNotExist:
        response_data['result'] = 'Пользователь с такими данными не зарегестрирован'
        logging.error('failed password recovery attempt (user \'{}\' does not exist)'.format(user_login))

    return django.http.HttpResponse(json.dumps(response_data), content_type='application/json')


# @abinba response_data['result'] or response_data?
def change_email(request: django.http.HttpRequest):
    username = request.user.username
    email = request.POST.get('email')

    if django.contrib.auth.models.User.objects.filter(email=email).exists():
        response_data = 'Такой эмейл уже зарегестрирован'
        logging.warning('failed email change attempt (existing email)')
    else:
        activation_key = activation_key_generator()
        app.models.UserEmail.objects.create_user_key(username, activation_key, email)

        subject = 'Изменение email аккаунта sharewood.online'
        message = ('Здравствуйте!\n'
                   'Перейдите по ссылке, чтобы подтвердить данный email: {}user/{}/change-email/\n\n'
                   'С уважением, команда Sharewood').format(address, username, activation_key)

        from_email = django.conf.settings.EMAIL_HOST_USER
        send_email = django.core.mail.send_mail(subject, message, from_email, [email])

        if send_email:
            response_data = 'Success!'
            logging.info('email change requested (username: \'{}\')'.format(username))
        else:
            response_data = 'Ошибка при отправке активационного письма'
            logging.error('failed email change attempt (error while sending a letter)')

    return django.http.HttpResponse(json.dumps(response_data), content_type='application/json')


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


import mimetypes
# TODO: output date and time for client time zone
# TODO: optimize search of similar records
# TODO: optimize search of media-content
# TODO: number of similar records depending on the number of comments
# TODO: more avatars load optimization
@django.views.decorators.csrf.csrf_exempt
@el_pagination.decorators.page_template('comments_list.html')
def record(request: django.http.HttpRequest,
           record_id: int,
           template: str = "record.html",
           extra_context: typing.Optional[dict] = None):

    records = app.models.Records.objects.all()
    prev_record = records[(record_id-1 or app.models.Records.objects.count()) - 1]
    current_record = records[record_id-1]
    next_record = records[record_id % app.models.Records.objects.count()]
    author = django.contrib.auth.models.User.objects.get(username=current_record.author)

    files = app.models.Media.objects.values_list('file1', 'file2', 'file3', 'file4', 'file5', 'file6',
        'file7', 'file8', 'file9', 'file10', 'file11', 'file12', 'file13', 'file14', 'file15', 'file16',
        'file17', 'file18', 'file19', 'file20', 'file21', 'file22', 'file23', 'file24', 'file25').filter(record_id=current_record.id)

    media = app.models.Media.objects.values_list('title').filter(record_id=current_record.id)

    content = []
    for title, flist in zip(media, files):
        flist = list(flist)
        for i, file in enumerate(flist):
            type, _ = mimetypes.guess_type(file)
            if not type:
                if file: logging.error('can\'t guess file type: {}'.format(file))
                continue
            if type.split('/')[0] == 'video':
                flist[i] = 'V{}'.format(file)
            elif type.split('/')[0] == 'audio':
                flist[i] = 'A{}'.format(file)
            elif type.split('/')[0] == 'text':
                flist[i] = 'F{}'.format(file)
            else:
                logging.error('can\'t guess file type: {}'.format(file))
        content.append([*title, flist])
    
    similar_records = []
    for tag in current_record.tags.split(', '):
        for r in app.models.Records.objects.filter(django.db.models.Q(tags__contains=tag)):
            if r not in similar_records and r != current_record:
                similar_records.append(r)
    random.shuffle(similar_records)
    if len(similar_records) > 1:
        similar_records = similar_records[:2]
    elif len(similar_records) > 0:
        similar_records = similar_records[:1]

    if request.POST.get('add_comment'):
        app.models.Comments.objects.create(author=request.POST.get('username'),
                                           avatar=app.models.Profile.objects.get(user_id=django.contrib.auth.models.User.objects.get(username=request.POST.get('username')).id).avatar,
                                           text=request.POST.get('add_comment'),
                                           date=datetime.datetime.now(),
                                           record_id=record_id)
        current_record.comments_count += 1
        current_record.save()

    if request.POST.get('action') == 'postratings':
        rate = int(request.POST.get('rate'))
        current_record.rating_sum += rate
        current_record.rating_count += 1
        current_record.rating = int((current_record.rating_sum / current_record.rating_count) * 10) / 10
        current_record.best_rating = max(rate, current_record.best_rating)
        current_record.worst_rating = min(rate, current_record.worst_rating)
        current_record.rated_users += request.user.username + ' '
        current_record.save()

    comments = list(app.models.Comments.objects.filter(record_id=record_id))

    if current_record.provided_users.find(request.user.username) != -1:
        is_provided = True
    else:
        is_provided = False

    context = {
        'prev_record': prev_record,
        'record': current_record,
        'next_record': next_record,
        'author': author,
        'profile': app.models.Profile.objects.get(user_id=author.id),
        'similar_records': similar_records,
        'comments': comments,
        'content': content,
        'is_provided': is_provided,
    }

    if extra_context is not None:
        context.update(extra_context)

    return django.shortcuts.render(request, template, context)


@el_pagination.decorators.page_template('records_list.html')
def records_by_tags(request: django.http.HttpRequest,
                    tag: str,
                    template: str = 'records_by_tag.html',
                    extra_context: typing.Optional[dict] = None):

    records = app.models.Records.objects.filter(django.db.models.Q(tags__contains=tag))
    context = {
        'tag': tag,
        'records': records,
    }

    if extra_context is not None:
        context.update(extra_context)

    return django.shortcuts.render(request, template, context)


# TODO: improve search engine
@el_pagination.decorators.page_template('records_list.html')
def search(request: django.http.HttpRequest, template: str = 'search.html',
           extra_context: typing.Optional[dict] = None):
    search_text = request.GET.get('s', default=' ')
    records = list(app.models.Records.objects.all())

    found_records = []
    for r in records:
        if ((search_text.lower() in r.title.lower()) or
            (search_text.lower() in r.description.lower()) or
            (search_text.lower() in r.text.lower()) or
            (search_text.lower() in r.author.lower()) or
            (search_text.lower() in r.tags.lower())):
            found_records.append(r)

    context = {
        'search': search,
        'records': found_records,
    }

    if extra_context is not None:
        context.update(extra_context)

    return django.shortcuts.render(request, template, context)


def api(request: django.http.HttpRequest, data: string):
    if data in ['registration', 'activity', 'sales']:
        return getattr(app.views_api, data)()
    else:
        response = django.shortcuts.render(request, '404.html')
        response.status_code = 404
        return response


def statistics(request: django.http.HttpRequest):
    return django.shortcuts.render(request, 'statistics.html')


# ----------------------------- functions -------------------------------
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


# TODO: handle secret_keys identity exception
def send_activation_email(username: str, email: str):
    activation_key = activation_key_generator()
    app.models.UserActivation.objects.create_user_key(username, activation_key)

    subject = 'Активация аккаунта sharewood.online'
    message = ('Здравствуйте! \n'
               'Вы зарегестирорвались на сайте sharewood.online. Перейдите по ссылке, чтобы активировать '
               'ваш аккаунт: {}user/{}/activate/{} \n\n'
               'С уважением, команда Sharewood').format(address, username, activation_key)

    from_email = django.conf.settings.EMAIL_HOST_USER
    return django.core.mail.send_mail(subject, message, from_email, [email])


def activation_key_generator(size: int = 40,
                             chars: string = string.ascii_uppercase + string.digits + string.ascii_lowercase):
    return ''.join(random.choice(chars) for _ in range(size))



# TODO: double buy
# TODO: is user active
def buy(request, record_id):
    message = 0
    if request.user.is_active:
        record = app.models.Records.objects.get(id=record_id)
        balance = request.user.profile.balance
        new_balance = balance - record.price
        if new_balance < 0:
            message = "NOT_ENOUGH"
        else :    
            request.user.profile.balance = new_balance
            request.user.save()

            provided_users = record.provided_users
            if provided_users == None or provided_users == "":
                provided_users = request.user.username
            else:
                provided_users += ", {}".format(request.user.username)
            record.provided_users = provided_users

            today = datetime.date.today() + datetime.timedelta(days=1)
            try:
                obj = app.models.Revenue.objects.get(date=today)
                obj.income += record.price
                obj.save()
            except django.core.exceptions.ObjectDoesNotExist:
                app.models.Revenue.objects.create(date=today, income=record.price)

            record.sales += 1
            record.save()
    else:
        message = "ACTIVATE"

    return django.shortcuts.redirect('/r{}/?message={}'.format(record_id, message))


# TODO: double buy
# TODO: is user active
def buy_premium(request):
    message = 0

    if request.user.is_active:
        premium = app.models.Premium.objects.get(id=1)
        cost = premium.premium_cost
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

    return django.shortcuts.redirect('/user/{}/cabinet#list-buy-premium'.format(request.user.username))
