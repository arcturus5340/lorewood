import django.http
import django.contrib.auth.models

import app.models
import app.forms

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
import django.utils.timezone
import django.views.decorators.csrf

import collections
import datetime
import string


def api(request: django.http.HttpRequest, data: string):
    if data == 'activity':
        return activity()
    if data == 'sales':
        return sales()


def statistics(request: django.http.HttpRequest):
    dates = django.contrib.auth.models.User.objects.order_by('date_joined').values_list('date_joined', 'last_login')

    class OrderedCounter(collections.Counter, collections.OrderedDict):
        pass

    registration_dates = OrderedCounter()

    last_login_dates = {
        'За последнюю неделю': 0,
        'За последний месяц': 0,
        'За поледний год и более': 0,
    }

    for join_date, login_date in dates:
        registration_dates[join_date.strftime('%d %B %Y')] += 1
        if (django.utils.timezone.now() - datetime.timedelta(weeks=1)) < login_date:
            last_login_dates['За последнюю неделю'] += 1
        elif (django.utils.timezone.now() - datetime.timedelta(days=30)) < login_date:
            last_login_dates['За последний месяц'] += 1
        else:
            last_login_dates['За поледний год и более'] += 1

    context = {
        'registration_dates': list(registration_dates.keys()),
        'registration_count': list(registration_dates.values()),
        'last_login_dates': list(last_login_dates.keys()),
        'last_login_count': list(last_login_dates.values()),
    }
    print(context)

    return django.shortcuts.render(request, 'statistics.html', context)


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
