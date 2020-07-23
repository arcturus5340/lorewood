from django.contrib import auth
from django.contrib.auth.models import User
from django.core import exceptions
from django.http.request import HttpRequest
from django.shortcuts import redirect, render

import logging

from app.models import ActivationKey

logger = logging.getLogger('app')


# TODO: specify in the logs on which event the failure occurred
def activate_setting(request: HttpRequest, username: str, activation_key: str):
    try:
        user = User.objects.get(username=username)
        activation_obj = ActivationKey.objects.get(user=user)
        if activation_obj.activation_key == activation_key:
            if activation_obj.is_email_change:
                user.email = activation_obj.new_email
                user.save()

            if activation_obj.is_registration:
                user.profile.is_verified = True
                user.save()

            if activation_obj.is_remember:
                request.user = user
                return render(request, 'password_recovery.html', {'activation_key': activation_key})

            activation_obj.delete()
            auth.login(request, user, backend='django.contrib.auth.backends.ModelBackend')
            return redirect('/')

        else:
            logger.warning('Setting activation fail: Wrong activation key')

    except exceptions.MultipleObjectsReturned:
        logger.warning('DataBase error: Multiple activation objects are returned for one username')
    except exceptions.ObjectDoesNotExist:
        logger.warning('Activation fail: Wrong username')

    return render(request, 'invalid_activation_key.html')
