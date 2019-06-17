import json
import django.http
import django.shortcuts
import django.contrib.auth
import django.contrib.auth.models
import django.contrib.auth.forms
import django_registration.backends.activation.views
import django_registration.forms

def index(request):
    regform = django_registration.forms.RegistrationForm
    authform = django.contrib.auth.forms.AuthenticationForm
    authnext = "/"
    return django.shortcuts.render(request, 'index.html', {'form' : authform, 'next' : authnext, 'regform' : regform })

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
    
    regform = django_registration.forms.RegistrationForm(request.POST)
    regview = django_registration.backends.activation.views.RegistrationView()
    response_data = {}

    registrated = False
    if(password1 != password2):
        response_data['result'] = "Пароли не совпадают"
    else:    
        try:
            user = regview.create_inactive_user(regform)
            registrated = True
        except ValueError as err:
            response_data['result'] = "Произошла ошибка при валидации"
        except AttributeError:
            registrated = True

    if registrated:
        response_data['result'] = "Success!" 

    return django.http.HttpResponse(json.dumps(response_data), content_type="application/json")

    # response_data = {}
    # if password1[0] == password2[0]:
    #     user = django.contrib.auth.models.User.objects.create_user(username[0], email[0], password1[0])
    # else:
    #     response_data['result'] = "Ошибка! Пароли не совпадают."

    # if user is not None:
    #     response_data['result'] = 'Success!'
    # else:
    #     response_data['result'] = user

    # return django.http.HttpResponse(json.dumps(response_data), content_type="application/json")
    # # Registration function


