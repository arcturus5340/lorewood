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
import el_pagination.decorators

import datetime
import json
import PIL.Image
import random
import re
import string
import typing

import app.forms
import app.models

address = 'http://127.0.0.1:8000/'


# TODO: calculating popular records once in a while
@el_pagination.decorators.page_template('records_list.html')
def index(request: django.http.HttpRequest, template: str = 'index.html', extra_context: typing.Optional[dict] = None):
    regform = django_registration.forms.RegistrationForm
    authform = django.contrib.auth.forms.AuthenticationForm
    authnext = "/"

    records = list(app.models.Records.objects.all())
    last_records = records[-4:]
    popular_records = sorted(records, key=lambda obj: obj.rating)[-5:]

    context = {
        'regform': regform,
        'form': authform,
        'next': authnext,
        'records': records,
        'last_records': last_records,
        'popular_records': popular_records,
        'popular_records_template': 'popular_records_template.html',
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
        response_data['result'] = 'Success!'
        django.contrib.auth.login(request, user)
    else:
        response_data['result'] = 'Failed!'

    return django.http.HttpResponse(json.dumps(response_data), content_type="application/json")


def logout(request: django.http.HttpRequest):
    django.contrib.auth.logout(request)
    return django.shortcuts.redirect("/")


def register(request: django.http.HttpRequest):
    username = request.POST.get('username')
    email = request.POST.get('email')
    password1 = request.POST.get('password1')
    password2 = request.POST.get('password2')

    response_data = {}
    if password1 != password2:
        response_data['result'] = 'Пароли не совпадают'

    # TODO: support for special characters in login
    elif not re.match(r'^[a-zA-z]+([a-zA-Z0-9]|_|\.)*$', username):
        response_data['result'] = ('Логин должен начинаться с латинской буквы, '
                                   'а также состоять только из латинских букв, цифр и символов . и _ ')

    elif django.contrib.auth.models.User.objects.filter(email=email):
        response_data['result'] = 'Такой эмейл уже зарегестрирован'

    else:
        response_data['result'] = validate_password(password1)
        if not response_data['result']:
            try:
                reguser = django.contrib.auth.models.User.objects.create_user(username, email, password1)
                if send_activation_email(username=username, email=email):
                    user = django.contrib.auth.authenticate(username=username, password=password1)
                    django.contrib.auth.login(request, user)
                    reguser.is_active = False
                    reguser.save()
                    response_data['result'] = 'Success!'
                else:
                    response_data['result'] = 'Ошибка при отправке письма с активацией'
            except ValueError:
                response_data['result'] = 'Произошла ошибка при валидации'
            except django.db.utils.IntegrityError:
                response_data['result'] = 'Такой юзернейм уже зарегестрирован'

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


# TODO: add an exception list
def activate_account(request: django.http.HttpRequest, username: str, activation_key: str):
    try:
        if app.models.UserActivation.objects.get(username=username).activation_key == activation_key:
            this_user = django.contrib.auth.models.User.objects.get(username=username)
            this_user.is_active = True
            this_user.save()

            django.contrib.auth.login(request, this_user, backend='django.contrib.auth.backends.ModelBackend')
            app.models.UserActivation.objects.get(username=username).delete()
            return django.shortcuts.redirect("/")
    except Exception:
        pass

    template = django.template.loader.get_template('../templates/invalid_activation_key.html')
    return django.http.HttpResponse(template.render())


def save_personal_data(request: django.http.HttpRequest):
    username = request.user.username
    try:
        im = PIL.Image.open(request.FILES.get('avatar'))
        width, height = im.size  # Get dimensions

        filename = '/media/avatars/cropped/cropped-{}crop.jpg'.format(username)

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

        user = django.contrib.auth.models.User.objects.get(username=username)
        user.first_name = request.POST.get('first_name')
        user.last_name = request.POST.get('last_name')
        user.profile.bio = request.POST.get('bio')
        user.profile.avatar = "/" + filename
        user.save()

    except Exception:
        print('Image does not exist')

    return django.shortcuts.redirect("/user/{}/cabinet".format(username))


def cabinet(request: django.http.HttpRequest, username: str):
    if request.user.username == username:
        return django.shortcuts.render(request, 'user/cabinet.html')
    return django.shortcuts.redirect('/')


# TODO: except: print('Error'): what kind of error?
def password_change_view(request: django.http.HttpRequest, username: str, activation_key: str):
    try:
        if app.models.UserActivation.objects.get(username=username).activation_key != activation_key:
            template = django.template.loader.get_template('../templates/invalid_activation_key.html')
            return django.http.HttpResponse(template.render())
    except Exception:
        print('Error')

    return django.shortcuts.render(request, 'user/password_change.html', {'username': username})


def change_password(request: django.http.HttpRequest, username: str):
    new_password1 = request.POST.get('new_password1')
    new_password2 = request.POST.get('new_password2')
    response_data = {}

    if new_password1 != new_password2:
        response_data['result'] = 'Пароли не совпадают'
    else:
        response_data['result'] = validate_password(new_password1)
        if not response_data['result']:
            user = django.contrib.auth.models.User.objects.get(username=username)
            user.set_password(new_password1)
            user.save()

            django.contrib.auth.login(request, user, backend='django.contrib.auth.backends.ModelBackend')
            # Are you sure, that you need to delete record from UserActivation?
            app.models.UserActivation.objects.get(username=username).delete()
            response_data['result'] = 'Success!'

    return django.http.HttpResponse(json.dumps(response_data), content_type='application/json')


def change_cabinet_password(request: django.http.HttpRequest):
    username = request.user.username
    new_password1 = request.POST.get('new_password1')
    new_password2 = request.POST.get('new_password2')
    response_data = {}

    if new_password1 != new_password2:
        response_data['result'] = 'Пароли не совпадают'
    else:
        response_data['result'] = validate_password(new_password1)
        if not response_data['result']:
            user = django.contrib.auth.models.User.objects.get(username=username)
            user.set_password(new_password1)
            user.save()

            django.contrib.auth.login(request, user, backend='django.contrib.auth.backends.ModelBackend')
            response_data['result'] = 'Success!'

    return django.http.HttpResponse(json.dumps(response_data), content_type='application/json')


# TODO: handle a failed data retrieval attempt
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
            else:
                response_data['result'] = 'Ошибка при отправлении письма'
        else:
            response_data['result'] = 'Пользователь не подтвердил свою электронную почту'
    except django.core.exceptions.MultipleObjectsReturned:
        response_data['result'] = 'Ошибка безопасности. Зарегистрирован еще один пользователь с такими данными'
    except django.core.exceptions.ObjectDoesNotExist:
        response_data['result'] = 'Пользователь с такими данными не зарегестрирован'

    return django.http.HttpResponse(json.dumps(response_data), content_type='application/json')


# response_data['result'] or response_data?
def change_email(request: django.http.HttpRequest):
    username = request.user.username
    email = request.POST.get('email')

    if django.contrib.auth.models.User.objects.get(email=email):
        response_data = 'Такой эмейл уже зарегестрирован'
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
        else:
            response_data = 'Ошибка при отправке письма с активацией'

    return django.http.HttpResponse(json.dumps(response_data), content_type='application/json')


# TODO: add an exception list
def change_email_confirm(request: django.http.HttpRequest, username, activation_key):

    try:
        compare_data = app.models.UserEmail.objects.get(username=username)
        if compare_data.activation_key != activation_key:
            template = django.template.loader.get_template('../templates/invalid_activation_key.html')
            return django.http.HttpResponse(template.render())

        user = django.contrib.auth.models.User.objects.get(username=username)
        user.email = compare_data.email
        user.is_active = True
        user.save()

        django.contrib.auth.login(request, user, backend='django.contrib.auth.backends.ModelBackend')
        app.models.UserEmail.objects.get(username=username).delete()

    except Exception:
        print("Error")

    return django.shortcuts.redirect(request, '/user/profile.html')


# TODO: output date and time for client time zone
# TODO: optimize search of similar records
# TODO: number of similar records depending on the number of comments
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
                                           text=request.POST.get('add_comment'),
                                           date=datetime.datetime.now(),
                                           record_id=record_id)
    comments = list(app.models.Comments.objects.filter(record_id=record_id))

    context = {
        'prev_record': prev_record,
        'record': current_record,
        'next_record': next_record,
        'author': author,
        'similar_records': similar_records,
        'comments': comments,
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
    popular_records = sorted(list(app.models.Records.objects.all()), key=lambda obj: obj.rating)[-5:]

    context = {
        'tag': tag,
        'records': records,
        'popular_records_template': 'popular_records_template.html',
        'popular_records': popular_records,
    }

    if extra_context is not None:
        context.update(extra_context)

    return django.shortcuts.render(request, template, context)


# TODO: improve search engine
# TODO: calculating popular records once in a while
@el_pagination.decorators.page_template('records_list.html')
def search(request: django.http.HttpRequest, template: str = 'search.html',
           extra_context: typing.Optional[dict] = None):
    search_text = request.GET.get('s', default=' ')
    records = list(app.models.Records.objects.all())
    popular_records = sorted(records, key=lambda obj: obj.rating)[-5:]

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
        'popular_records_template': 'popular_records_template.html',
        'popular_records': popular_records,
    }

    if extra_context is not None:
        context.update(extra_context)

    return django.shortcuts.render(request, template, context)


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
