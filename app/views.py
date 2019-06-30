import django.http
import django.shortcuts
import django.conf
import django.core.exceptions
import django.contrib.auth
import django.contrib.auth.password_validation
import django.contrib.auth.models
import django.contrib.auth.forms
import django.template
import django_registration.backends.activation.views
import django_registration.forms
import django.db.utils
import django.core.paginator
import django.core.mail
import el_pagination.decorators

import json
import PIL
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


# TODO: improve search engine
# TODO: calculating popular records once in a while
@el_pagination.decorators.page_template('records_list.html')
def search(request: django.http.HttpRequest, template: str = 'search.html',
           extra_context: typing.Optional[dict] = None):
    search_text = request.GET.get('s', default=' ')
    records = list(app.models.Records.objects.all())
    popular_records = sorted(records, key=lambda obj: obj.rating)[-5:]

    found_records = []
    for record in records:
        if ((search_text.lower() in record.title.lower()) or
                (search_text.lower() in record.description.lower()) or
                (search_text.lower() in record.text.lower()) or
                (search_text.lower() in record.author.lower()) or
                (search_text.lower() in record.tags.lower())):
            found_records.append(record)

    context = {
        'search': search,
        'records': found_records,
        'popular_records_template': 'popular_records_template.html',
        'popular_records': popular_records,
    }

    if extra_context is not None:
        context.update(extra_context)

    return django.shortcuts.render(request, template, context)


def login(request):
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


def logout(request):
    django.contrib.auth.logout(request)
    return django.shortcuts.redirect("/")


# Watafak
def validate_password(password):
    try:
        django.contrib.auth.password_validation.validate_password(password)
    except django.core.exceptions.ValidationError as err:
        print(err)
        error_str = str(err)
        if error_str.find('too short') != -1:
            return 'Пароль слишком короткий. Минимальное количество - 8 символов'
        elif error_str.find('too common') != -1:
            return 'Пароль слишком предсказуемый'
        else:
            return 'Пароль содержит недопустимые символы'
    return


def send_activation_email(username, email):
    activation_key = activation_key_generator()
    app.models.UserActivation.objects.create_user_key(username, activation_key)

    subject = 'Активация аккаунта sharewood.online'
    message = ('Здравствуйте! Вы зарегестирорвались на сайте sharewood.online. Перейдите по ссылке, чтобы активировать '
               'ваш аккаунт: {}user/{}/activate/{} '
               'С уважением, команда Sharewood').format(address, username, activation_key)

    from_email = django.conf.settings.EMAIL_HOST_USER
    return django.core.mail.send_mail(subject, message, from_email, [email])


def activation_key_generator(size=40, chars=string.ascii_uppercase + string.digits + string.ascii_lowercase):
    return ''.join(random.choice(chars) for _ in range(size))


def register(request):
    username = request.POST.get('username')
    email = request.POST.get('email')
    password1 = request.POST.get('password1')
    password2 = request.POST.get('password2')

    response_data = {}
    if password1 != password2:
        response_data['result'] = 'Пароли не совпадают'

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


def advertising(request):
    return django.shortcuts.render(request, "advertising.html")


def donations(request):
    return django.shortcuts.render(request, "donations.html")


def info(request):
    return django.shortcuts.render(request, "info.html")


def regulations(request):
    return django.shortcuts.render(request, "regulations.html")


def rightholder(request):
    return django.shortcuts.render(request, "rightholder.html")


import datetime


@el_pagination.decorators.page_template('comments_list.html')
def record(request, record_id, template="record.html", extra_context=None):
    if request.POST.getlist("add_comment"):
        app.models.Comments.objects.create(author=request.POST.getlist("username")[0],
                                           text=request.POST.getlist("add_comment")[0],
                                           date=datetime.datetime.now(),
                                           record_id=record_id)

    comments = list(app.models.Comments.objects.filter(record_id=record_id))

    try:
        prev_record = app.models.Records.objects.get(id=(record_id - 1) or app.models.Records.objects.count())
    except app.models.Records.DoesNotExist:
        prev_record = 0

    record = app.models.Records.objects.get(id=record_id)
    similar_records = []
    for tag in record.tags.split(", "):
        for r in app.models.Records.objects.filter(django.db.models.Q(tags__contains=tag)):
            if r not in similar_records and r != record:
                similar_records.append(r)

    random.shuffle(similar_records)
    if len(similar_records) > 1:
        similar_records = similar_records[:2]
    elif len(similar_records) > 0:
        similar_records = similar_records[:1]
    record_id = 1 if record_id + 1 >= app.models.Records.objects.count() else record_id + 1
    next_record = app.models.Records.objects.get(id=record_id)
    author = django.contrib.auth.models.User.objects.get(username=record.author)

    placeholder = request.POST.getlist('quote') or ['', ]
    context = {
        'prev_record': prev_record,
        'record': record,
        'next_record': next_record,
        'author': author,
        'similar_records': similar_records,
        'comments': comments,
        'placeholder': placeholder[0],
    }

    if extra_context is not None:
        context.update(extra_context)

    return django.shortcuts.render(request, template, context)


@el_pagination.decorators.page_template('records_list.html')
def records_by_tags(request, tag, template='records_by_tag.html', extra_context=None):
    records = app.models.Records.objects.filter(django.db.models.Q(tags__contains=tag))
    popular_records = list(app.models.Records.objects.order_by('rating'))[-5:]

    context = {
        'tag': tag,
        'records': records,
        'popular_records_template': 'popular_records_template.html',
        'popular_records': popular_records,
    }

    if extra_context is not None:
        context.update(extra_context)

    return django.shortcuts.render(request, template, context)


def activate_account(request, username, activation_key):
    invalid = False
    try:
        compare_data = app.models.UserActivation.objects.get(username=username)

        if compare_data.activation_key == activation_key:
            this_user = django.contrib.auth.models.User.objects.get(username=username)
            this_user.is_active = True
            this_user.save()

            django.contrib.auth.login(request, this_user, backend='django.contrib.auth.backends.ModelBackend')
            app.models.UserActivation.objects.get(username=username).delete()

            return django.shortcuts.redirect("/")
        else:
            invalid = True
    except:
        invalid = True

    if invalid:
        template = django.template.loader.get_template('../templates/invalid_activation_key.html')
        return django.http.HttpResponse(template.render())


def remember(request):
    user_login = request.POST["user_login"]
    user_exists = False
    response_data = {}

    try:
        this_user = django.contrib.auth.models.User.objects.get(username=user_login)
        user_exists = True
    except:
        try:
            this_user = django.contrib.auth.models.User.objects.get(email=user_login)
            user_exists = True
        except:
            response_data['result'] = "Пользователь с такими данными не зарегестрирован"

    if user_exists:
        if this_user.is_active:
            activation_key = activation_key_generator()
            user_activation = app.models.UserActivation.objects.create_user_key(this_user.username, activation_key)

            subject = "Восстановление аккаунта sharewood.online"
            message = "Здравствуйте! Перейдите по ссылке, чтобы поменять ваш пароль: " + address + "user/" + this_user.username + "/remember/" + activation_key + " С уважением, команда Sharewood"
            from_email = django.conf.settings.EMAIL_HOST_USER
            to_list = [this_user.email]

            send_email = django.core.mail.send_mail(subject, message, from_email, to_list)
            if send_email:
                response_data['result'] = "Success!"
            else:
                response_data['result'] = "Ошибка при отправлении письма"

        else:
            response_data['result'] = "Пользователь не подтвердил свою электронную почту"
    return django.http.HttpResponse(json.dumps(response_data), content_type="application/json")


def password_change_view(request, username, activation_key):
    invalid = True

    try:
        compare_data = app.models.UserActivation.objects.get(username=username)
        if compare_data.activation_key == activation_key:
            invalid = False
    except:
        print("Error")

    if invalid:
        template = django.template.loader.get_template('../templates/invalid_activation_key.html')
        return django.http.HttpResponse(template.render())
    else:
        return django.shortcuts.render(request, 'user/password_change.html', {'username': username})


def change_password(request, username):
    new_password1 = request.POST["new_password1"]
    new_password2 = request.POST["new_password2"]
    response_data = {}

    if new_password1 == new_password2:
        validate_pass = validate_password(new_password1)
        if validate_pass == "no error":
            user = django.contrib.auth.models.User.objects.get(username=username)
            user.set_password(new_password1)
            user.save()

            django.contrib.auth.login(request, user, backend='django.contrib.auth.backends.ModelBackend')

            response_data['result'] = 'Success!'
            app.models.UserActivation.objects.get(username=username).delete()
        else:
            response_data['result'] = validate_pass
    else:
        response_data['result'] = "Пароли не совпадают"
    return django.http.HttpResponse(json.dumps(response_data), content_type="application/json")


def change_cabinet_password(request):
    username = request.user.username
    new_password1 = request.POST["new_password1"]
    new_password2 = request.POST["new_password2"]
    response_data = {}

    if new_password1 == new_password2:
        validate_pass = validate_password(new_password1)
        if validate_pass == "no error":
            user = django.contrib.auth.models.User.objects.get(username=username)
            user.set_password(new_password1)
            user.save()

            django.contrib.auth.login(request, user, backend='django.contrib.auth.backends.ModelBackend')

            response_data['result'] = 'Success!'
        else:
            response_data['result'] = validate_pass
    else:
        response_data['result'] = "Пароли не совпадают"
    return django.http.HttpResponse(json.dumps(response_data), content_type="application/json")


def change_email(request):
    user = django.contrib.auth.models.User.objects.get(email=request.POST['email'])
    if user:
        response_data = "Такой эмейл уже зарегестрирован"
    else:
        activation_key = activation_key_generator()
        user_activation = app.models.UserEmail.objects.create_user_key(request.user.username, activation_key,
                                                                       request.POST['email'])
        subject = "Изменение email аккаунта sharewood.online"
        message = "Здравствуйте! Перейдите по ссылке, чтобы подтвердить данный email: " + address + "user/" + request.user.username + "/change-email/" + activation_key + " С уважением, команда Sharewood"
        from_email = django.conf.settings.EMAIL_HOST_USER
        to_list = [request.POST["email"]]

        send_email = django.core.mail.send_mail(subject, message, from_email, to_list)

        if send_email:
            response_data = "Success!"

    return django.http.HttpResponse(json.dumps(response_data), content_type="application/json")


def change_email_confirm(request, username, activation_key):
    invalid = True

    try:
        compare_data = app.models.UserEmail.objects.get(username=username)
        if compare_data.activation_key == activation_key:
            invalid = False
    except:
        print("Error")

    if invalid:
        template = django.template.loader.get_template('../templates/invalid_activation_key.html')
        return django.http.HttpResponse(template.render())
    else:
        user = django.contrib.auth.models.User.objects.get(username=username)
        user.email = compare_data.email
        user.is_active = True
        user.save()

        django.contrib.auth.login(request, user, backend='django.contrib.auth.backends.ModelBackend')
        app.models.UserEmail.objects.get(username=username).delete()
        return django.shortcuts.redirect(request, '/user/profile.html')


def cabinet(request, username):
    user = request.user
    if user.username == username:
        return django.shortcuts.render(request, 'user/cabinet.html')

    return django.shortcuts.redirect("/")


def save_personal_data(request):
    filename = "default"
    try:
        im = PIL.Image.open(request.FILES.get("avatar"))
        width, height = im.size  # Get dimensions

        filename = "media/avatars/cropped/cropped-" + request.user.username + "crop.jpg"

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
    except:
        print("Image does not exist")

    user = django.contrib.auth.models.User.objects.get(username=request.user.username)
    user.first_name = request.POST.get('first_name')
    user.last_name = request.POST.get('last_name')
    user.profile.bio = request.POST.get('bio')

    if filename != "default":
        user.profile.avatar = "/" + filename

    myuser = user.save()

    return django.shortcuts.redirect("/user/" + request.user.username + "/cabinet")
