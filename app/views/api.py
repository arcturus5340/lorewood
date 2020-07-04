import django.http
import django.contrib.auth.models

import app.models

import collections
import datetime
import django.contrib.auth
import django.contrib.auth.forms
import django.contrib.auth.models
import django.contrib.auth.password_validation
import django.core.exceptions
import django.core.mail
import django.core.paginator
import django.db.models
import django.db.utils
import django.http
import django.shortcuts
import django.template.loader
# import django_registration.backends.activation.views
# import django_registration.forms
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
import sys
import typing

import app.forms
import app.models

def api(request: django.http.HttpRequest, data: string):
    if data in ['registration', 'activity', 'sales']:
        return getattr(data)()
    else:
        response = django.shortcuts.render(request, '404.html')
        response.status_code = 404
        return response


def statistics(request: django.http.HttpRequest):
    return django.shortcuts.render(request, 'statistics.html')

import django.http
import django.contrib.auth.models

import app.models

import collections
import datetime


def registration():
    users = django.contrib.auth.models.User.objects.all()
    data = collections.Counter(format_date(obj.date_joined) for obj in users).items()
    print(data)
    data = collections.OrderedDict({key: val for key, val in sorted(data)})
    return django.http.JsonResponse(data)


def activity():
    users = django.contrib.auth.models.User.objects.all()
    data = {
        'За последнюю неделю': 0,
        'За последний месяц': 0,
        'За поледний год и более': 0,
    }
    for user in users:
        if (datetime.date.today() - datetime.timedelta(weeks=1)) < user.last_login.date():
            data['За последнюю неделю'] += 1
        elif (datetime.date.today() - datetime.timedelta(days=30)) < user.last_login.date():
            data['За последний месяц'] += 1
        else:
            data['За поледний год и более'] += 1

    return django.http.JsonResponse(data)


def sales():
    data = {format_date(key): val for key, val in app.models.Revenue.objects.values_list('date', 'income')}
    return django.http.JsonResponse(data, safe=False)


def format_date(date):
    sdate = str(date)[:10].split('-')
    month = int(sdate[1])
    if month == 1:
        sdate[1] = 'Января'
    elif month == 2:
        sdate[1] = 'Февраля'
    elif month == 3:
        sdate[1] = 'Марта'
    elif month == 4:
        sdate[1] = 'Апреля'
    elif month == 5:
        sdate[1] = 'Мая'
    elif month == 6:
        sdate[1] = 'Июня'
    elif month == 7:
        sdate[1] = 'Июля'
    elif month == 8:
        sdate[1] = 'Августа'
    elif month == 9:
        sdate[1] = 'Сентября'
    elif month == 10:
        sdate[1] = 'Октябрь'
    elif month == 11:
        sdate[1] = 'Ноябрь'
    elif month == 12:
        sdate[1] = 'Декабрь'
    return " ".join(reversed(sdate))
