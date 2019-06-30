import django.forms
import django.contrib.auth.models

import app.models


class UserForm(django.forms.ModelForm):
    class Meta:
        model = django.contrib.auth.models.User
        fields = ('first_name', 'last_name')


class ProfileForm(django.forms.ModelForm):
    class Meta:
        model = app.models.Profile
        fields = ('avatar', 'bio')
