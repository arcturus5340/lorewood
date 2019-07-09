import django.http
import django.contrib.auth.models

import app.models

import collections
import datetime


def registration():
    users = django.contrib.auth.models.User.objects.all()
    data = dict(collections.Counter(format_date(obj.date_joined) for obj in users))
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
