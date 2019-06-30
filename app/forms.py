from django import forms
from django.contrib.auth.models import User
from app.models import Profile
from app.models import Comments


class UserForm(forms.ModelForm):
    class Meta:
        model = User
        fields = ('first_name', 'last_name')

class ProfileForm(forms.ModelForm):
    class Meta:
        model = Profile
        fields = ('avatar', 'bio')
