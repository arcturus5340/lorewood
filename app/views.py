import django.http
import django.shortcuts
import django.contrib.auth
import django.contrib.auth.models
import django.contrib.auth.forms
import json

def index(request):
    authform = django.contrib.auth.forms.AuthenticationForm
    authnext = "/"
    return django.shortcuts.render(request, 'index.html', {'form' : authform, 'next' : authnext})

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
    username = request.POST
    # Registration function


