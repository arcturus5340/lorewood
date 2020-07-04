# -*- coding: utf-8 -*-

import django.conf
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

@el_pagination.decorators.page_template('records_list.html')
def index(request: django.http.HttpRequest, template: str = 'index.html', extra_context: typing.Optional[dict] = None):
    # regform = django_registration.forms.RegistrationForm
    # authform = django.contrib.auth.forms.AuthenticationForm
    # authnext = "/"

    records = app.models.Records.objects

    context = {
        # 'regform': regform,
        # 'form': authform,
        # 'next': authnext,
        'records': records.all(),
        'last_records': records.order_by('-rating')[:4],
    }

    if extra_context is not None:
        context.update(extra_context)

    return django.shortcuts.render(request, template, context)


import mimetypes


# TODO: output date and time for client time zone
# TODO: optimize search of similar records
# TODO: optimize search of media-content
# TODO: number of similar records depending on the number of comments
# TODO: more avatars load optimization
@django.views.decorators.csrf.csrf_exempt
@el_pagination.decorators.page_template('comments_list.html')
def record(request: django.http.HttpRequest,
           record_id: int,
           template: str = "record.html",
           extra_context: typing.Optional[dict] = None):
    records = app.models.Records.objects.all()
    prev_record = records[(record_id - 1 or app.models.Records.objects.count()) - 1]
    current_record = records[record_id - 1]
    next_record = records[record_id % app.models.Records.objects.count()]
    author = django.contrib.auth.models.User.objects.get(username=current_record.author)

    files = app.models.Media.objects.values_list('file1', 'file2', 'file3', 'file4', 'file5', 'file6',
                                                 'file7', 'file8', 'file9', 'file10', 'file11', 'file12', 'file13',
                                                 'file14', 'file15', 'file16',
                                                 'file17', 'file18', 'file19', 'file20', 'file21', 'file22', 'file23',
                                                 'file24', 'file25').filter(record_id=current_record.id)

    media = app.models.Media.objects.values_list('title').filter(record_id=current_record.id)

    content = []
    for title, flist in zip(media, files):
        file_list = []
        for i, file in enumerate(flist):
            type, _ = mimetypes.guess_type(file)
            if not type:
                if file: logging.error('can\'t guess file type: {}'.format(file))
                continue
            if type.split('/')[0] == 'video':
                file_list.append('V{}'.format(file))
            elif type.split('/')[0] == 'audio':
                file_list.append('A{}'.format(file))
            elif type.split('/')[0] == 'text':
                file_list.append('F{}'.format(file))
            else:
                logging.error('can\'t guess file type: {}'.format(file))
        content.append([*title, file_list])

    similar_records = []
    for tag in current_record.tags.split(', '):
        for r in app.models.Records.objects.filter(django.db.models.Q(tags__contains=tag)):
            if r not in similar_records and r != current_record:
                similar_records.append(r)
    random.shuffle(similar_records)
    if len(similar_records) > 1:
        similar_records = similar_records[:2]
    elif len(similar_records) > 0:
        similar_records = similar_records[:1]

    if request.POST.get('add_comment'):
        app.models.Comments.objects.create(author=request.POST.get('username'),
                                           avatar=app.models.Profile.objects.get(
                                               user_id=django.contrib.auth.models.User.objects.get(
                                                   username=request.POST.get('username')).id).avatar,
                                           text=request.POST.get('add_comment'),
                                           date=datetime.datetime.now(),
                                           record_id=record_id)
        current_record.comments_count += 1
        current_record.save()

    if request.POST.get('action') == 'postratings':
        rate = int(request.POST.get('rate'))
        current_record.rating_sum += rate
        current_record.rating_count += 1
        current_record.rating = int((current_record.rating_sum / current_record.rating_count) * 10) / 10
        current_record.best_rating = max(rate, current_record.best_rating)
        current_record.worst_rating = min(rate, current_record.worst_rating)
        current_record.rated_users += request.user.username + ' '
        current_record.save()

    comments = list(app.models.Comments.objects.filter(record_id=record_id))

    if request.user.username in current_record.provided_users.split(', '):
        is_provided = True
    else:
        is_provided = False

    context = {
        'prev_record': prev_record,
        'record': current_record,
        'next_record': next_record,
        'author': author,
        'profile': app.models.Profile.objects.get(user_id=author.id),
        'similar_records': similar_records,
        'comments': comments,
        'content': content,
        'is_provided': is_provided,
    }

    if extra_context is not None:
        context.update(extra_context)

    return django.shortcuts.render(request, template, context)


# TODO: double buy
# TODO: is user active
def buy(request, record_id):
    message = 0
    if request.user.is_active:
        record = app.models.Records.objects.get(id=record_id)
        balance = request.user.profile.balance
        new_balance = balance - record.price
        if new_balance < 0:
            message = "NOT_ENOUGH"
        else :
            request.user.profile.balance = new_balance
            request.user.save()

            provided_users = record.provided_users
            if provided_users == None or provided_users == "":
                provided_users = request.user.username
            else:
                provided_users += ", {}".format(request.user.username)
            record.provided_users = provided_users

            today = datetime.date.today() + datetime.timedelta(days=1)
            try:
                obj = app.models.Revenue.objects.get(date=today)
                obj.income += record.price
                obj.save()
            except django.core.exceptions.ObjectDoesNotExist:
                app.models.Revenue.objects.create(date=today, income=record.price)

            record.sales += 1
            record.save()
    else:
        message = "ACTIVATE"

    return django.shortcuts.redirect('/r{}/?message={}'.format(record_id, message))




@el_pagination.decorators.page_template('records_list.html')
def records_by_tags(request: django.http.HttpRequest,
                    tag: str,
                    template: str = 'records_by_tag.html',
                    extra_context: typing.Optional[dict] = None):

    records = app.models.Records.objects.filter(django.db.models.Q(tags__contains=tag))
    context = {
        'tag': tag,
        'records': records,
    }

    if extra_context is not None:
        context.update(extra_context)

    return django.shortcuts.render(request, template, context)


# TODO: improve search engine
@el_pagination.decorators.page_template('records_list.html')
def search(request: django.http.HttpRequest, template: str = 'search.html',
           extra_context: typing.Optional[dict] = None):
    search_text = request.GET.get('s', default=' ')
    records = list(app.models.Records.objects.all())

    found_records = []
    for r in records:
        if ((search_text.lower() in r.title.lower()) or
            (search_text.lower() in r.description.lower()) or
            (search_text.lower() in r.text.lower()) or
            (search_text.lower() in r.author.lower()) or
            (search_text.lower() in r.tags.lower())):
            found_records.append(r)

    context = {
        'search': search,
        'records': found_records,
    }

    if extra_context is not None:
        context.update(extra_context)

    return django.shortcuts.render(request, template, context)



def advertising(request: django.http.HttpRequest):
    return django.shortcuts.render(request, 'advertising.html')


def donations(request: django.http.HttpRequest):
    return django.shortcuts.render(request, 'donations.html')


def info(request: django.http.HttpRequest):
    return django.shortcuts.render(request, 'info.html')


def regulations(request: django.http.HttpRequest):
    return django.shortcuts.render(request, 'regulations.html')


def rightholder(request: django.http.HttpRequest):
    return django.shortcuts.render(request, 'rightholder.html')
