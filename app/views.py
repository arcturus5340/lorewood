import json
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
import string
import random
import re

import app.models
import datetime

address = "http://127.0.0.1:8000/"

def record(request):
    print(request.get_full_path())

def index(request):
    # b = app.models.Records.objects.create(title='Beatles Blog', text='All the latest Beatles news.', description='All the lqwdassdasdasdasdasdasdasdasdasdasdatest Beatles news.', rating=10.1)
    # b = app.models.Records.objects.create(title='Beatles Blog', text='All the latest Beatles news.', description='All the lqwdassdasdasdasdasdasdasdasdasdasdatest Beatles news.', rating=10.1)
    # b = app.models.Records.objects.create(title='Beatles Blog', text='All the latest Beatles news.', description='All the lqwdassdasdasdasdasdasdasdasdasdasdatest Beatles news.', rating=10.1)
    # b = app.models.Records.objects.create(title='Beatles Blog', text='All the latest Beatles news.', description='All the lqwdassdasdasdasdasdasdasdasdasdasdatest Beatles news.', rating=10.1)

    record_list = app.models.Records.objects.all()
    paginator = django.core.paginator.Paginator(record_list, 3)

    page = request.GET.get('page')
    records = paginator.get_page(page)

    regform = django_registration.forms.RegistrationForm
    authform = django.contrib.auth.forms.AuthenticationForm
    authnext = "/"

    return django.shortcuts.render(request, 'index.html', {
                                                           'form' : authform,
                                                           'next' : authnext,
                                                           'regform' : regform,
                                                           'records': records,
                                                           })


def login(request):
    if request.method == 'POST':
        user_login = request.POST.getlist('login')
        user_password = request.POST.getlist('password')
        user = django.contrib.auth.authenticate(username=user_login[0], password=user_password[0])
        response_data = {}
        if user is not None:
            response_data['result'] = 'Success!'
            django.contrib.auth.login(request, user)
        else:
            response_data['result'] = 'Failed!'

        return django.http.HttpResponse(json.dumps(response_data), content_type="application/json")


def logout(request):
    django.contrib.auth.logout(request)
    return django.shortcuts.redirect("/")


def register(request):
    username = request.POST.getlist('username')
    email = request.POST.getlist('email')
    password1 = request.POST.getlist('password1')
    password2 = request.POST.getlist('password2')
    
    response_data = {}

    error = ""
    registrated = False
    if(password1 != password2):
        response_data['result'] = "Пароли не совпадают"
    else:   
        compare_data = django.contrib.auth.models.User.objects.filter(email=email[0])
        if compare_data:
            response_data['result'] = "Такой эмейл уже зарегестрирован"
        else:
            try:
                validate = django.contrib.auth.password_validation.validate_password(password1[0])
                try:
                    reguser = django.contrib.auth.models.User.objects.create_user(username[0], email[0], password1[0])
                    registrated = True
                except ValueError as err:
                    error = "Произошла ошибка при валидации"
                except django.db.utils.IntegrityError:
                    error = "Такой юзернейм уже зарегестрирован"

            except django.core.exceptions.ValidationError as err:
                error_str = str(err)
                if error_str.find("too short") != -1:
                    error = "Пароль слишком короткий. Минимальное количество - 8 cимволов"
                elif error_str.find("too common") != -1:
                    error = "Пароль слишком предсказуемый"
                else:
                    error = "Пароль содержит недопустимые символы"
            
            response_data['result'] = error

    if re.match(r"[a-zA-z]+([a-zA-Z0-9]|_|.)*", username[0]):
        if registrated:
            user = django.contrib.auth.authenticate(username=username[0], password=password1[0])
            django.contrib.auth.login(request, user)
            reguser.is_active = False
            reguser.save()
            django.contrib.auth.login(request, user)

            activation_key = activation_key_generator()
            user_activation = app.models.UserActivation.objects.create_user_key(username[0], activation_key)

            subject = "Активация аккаунта sharewood.online"
            message = "Здравствуйте! Вы зарегестирорвались на сайт sharewood.online. Перейдите по ссылке, чтобы активировать ваш аккаунт: "+address+"user/"+username[0]+"/"+activation_key+" С уважением, команда Sharewood"
            from_email = django.conf.settings.EMAIL_HOST_USER
            to_list = [email[0]]

            django.core.mail.send_mail(subject, message, from_email, to_list)

            response_data['result'] = "Success!"
    else:
        response_data['result'] = "Неправильный логин. Он должен содержать только латинские буквы и числа и символы (. и _). Должен начинаться с буквы."

    return django.http.HttpResponse(json.dumps(response_data), content_type="application/json")


def advertising(request):
    template = django.template.loader.get_template('../templates/advertising.html')
    return django.http.HttpResponse(template.render())


def donations(request):
    template = django.template.loader.get_template('../templates/donations.html')
    return django.http.HttpResponse(template.render())


def info(request):
    template = django.template.loader.get_template('../templates/info.html')
    return django.http.HttpResponse(template.render())


def regulations(request):
    template = django.template.loader.get_template('../templates/regulations.html')
    return django.http.HttpResponse(template.render())


def rightholder(request):
    template = django.template.loader.get_template('../templates/rightholder.html')
    return django.http.HttpResponse(template.render())


def nazvanie_zapisi_8_nazvanie_zapisi_8(request):
    template = django.template.loader.get_template('../templates/nazvanie-zapisi-8-nazvanie-zapisi-8.html')
    return django.http.HttpResponse(template.render())

def activation_key_generator(size=40, chars=string.ascii_uppercase + string.digits + string.ascii_lowercase):
    return ''.join(random.choice(chars) for _ in range(size))

def activate_account(request, username, activation_key):
    try:
        compare_data = app.models.UserActivation.objects.get(username=username)
        
        if compare_data.activation_key == activation_key:
            this_user = django.contrib.auth.models.User.objects.get(username=username)
            this_user.is_active = True
            this_user.save()

            app.models.UserActivation.objects.get(username=username).delete()
            
            return django.shortcuts.redirect("/")
        else:
            template = django.template.loader.get_template('../templates/invalid_activation_key.html')
            return django.http.HttpResponse(template.render())
    except:
        template = django.template.loader.get_template('../templates/invalid_activation_key.html')
        return django.http.HttpResponse(template.render())
