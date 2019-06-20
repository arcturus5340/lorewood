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
            if re.match(r"^[a-zA-z]+([a-zA-Z0-9]|_|\.)*$", username[0]):
                
                validate_pass = validate_password(password1[0])
                
                if validate_pass == "no error":
                    try:
                        reguser = django.contrib.auth.models.User.objects.create_user(username[0], email[0], password1[0])
                        registrated = True
                    except ValueError as err:
                        error = "Произошла ошибка при валидации"
                    except django.db.utils.IntegrityError:
                        error = "Такой юзернейм уже зарегестрирован"
                else: 
                    error = validate_pass
                
                response_data['result'] = error
            else:
                response_data['result'] = "Неправильный логин. Он должен содержать только латинские буквы и числа и символы (. и _). Должен начинаться с буквы."

        if registrated:
            send_email = send_activation_email(username=username[0], email=email[0])

            if send_email:
                user = django.contrib.auth.authenticate(username=username[0], password=password1[0])
                django.contrib.auth.login(request, user)
                reguser.is_active = False
                reguser.save()  

                response_data['result'] = "Success!"
            else:
                response_data['result'] = "Ошибка при отправке письма с активацией"

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

def send_activation_email(username, email):
    activation_key = activation_key_generator()
    user_activation = app.models.UserActivation.objects.create_user_key(username, activation_key)

    subject = "Активация аккаунта sharewood.online"
    message = "Здравствуйте! Вы зарегестирорвались на сайт sharewood.online. Перейдите по ссылке, чтобы активировать ваш аккаунт: "+address+"user/"+username+"/activate/"+activation_key+" С уважением, команда Sharewood"
    from_email = django.conf.settings.EMAIL_HOST_USER
    to_list = [email]

    send_email = django.core.mail.send_mail(subject, message, from_email, to_list)
    return send_email

def validate_password(password):
    error = "no error"
    try:
        validate = django.contrib.auth.password_validation.validate_password(password)
    except django.core.exceptions.ValidationError as err:
        error_str = str(err)
        if error_str.find("too short") != -1:
            error = "Пароль слишком короткий. Минимальное количество - 8 cимволов"
        elif error_str.find("too common") != -1:
            error = "Пароль слишком предсказуемый"
        else:
            error = "Пароль содержит недопустимые символы"
    return error


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
            message = "Здравствуйте! Перейдите по ссылке, чтобы поменять ваш пароль: "+address+"user/"+this_user.username+"/remember/"+activation_key+" С уважением, команда Sharewood"
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
        return django.shortcuts.render(request, 'user/password_change.html', {'username':username})

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



