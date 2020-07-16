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
import django.views.decorators.csrf

import el_pagination.decorators

import datetime
import logging
import mimetypes
import random
import typing

import app.forms
from app.models import Files, Headers, Records, Rated_Users, Tags
from django.contrib.auth.models import User
from django.db.models import Q

@el_pagination.decorators.page_template('records_list.html')
def index(request: django.http.HttpRequest, template: str = 'index.html', extra_context: typing.Optional[dict] = None):
    records = Records.objects.order_by('-rating')

    context = {
        'records': records[4:],
        'last_records': records[:4],
    }

    if extra_context is not None:
        context.update(extra_context)

    return django.shortcuts.render(request, template, context)


# TODO: output date and time for client time zone
# TODO: optimize search of media-content
@el_pagination.decorators.page_template('comments_list.html')
def record(request: django.http.HttpRequest, record_id: int, template: str = "record.html",
           extra_context: typing.Optional[dict] = None):
    records_qs = Records.objects.all()
    current_record = records_qs.get(id=record_id)
    prev_record = records_qs.filter(pk__gt=current_record.pk).order_by('-pk').first()
    next_record = records_qs.filter(pk__gt=current_record.pk).order_by('pk').first()

    content = dict()
    for header in current_record.headers_set.all():
        content[header.title] = list()
        for file in header.files_set.all():
            file_type, _ = mimetypes.guess_type(file.src.name)
            if (not file_type) and file:
                if file:
                    logging.error('can\'t guess file type: {}'.format(file))
                continue
            if file_type.split('/')[0] == 'video':
                content[header.title].append(('V', file))
            elif file_type.split('/')[0] == 'audio':
                content[header.title].append(('A', file))
            elif file_type.split('/')[0] == 'text':
                content[header.title].append(('F', file))
            else:
                content[header.title].append(('U', file))

    same_tag_records = Records.objects.filter(tags__in=current_record.tags.all()).distinct()
    similar_records = same_tag_records.exclude(pk=current_record.pk)

    two_similar_records = (None, None)
    while two_similar_records[0] == two_similar_records[1]:
        similar_records_iterator = iter(similar_records)
        two_similar_records = (
            next(similar_records_iterator, random.choice(Records.objects.all())),
            next(similar_records_iterator, random.choice(Records.objects.all())),
        )

    if request.POST.get('add_comment'):
        app.models.Comments.objects.create(author=request.user,
                                           content=request.POST.get('add_comment'),
                                           date=datetime.datetime.now(),
                                           record=current_record)

    if request.POST.get('action') == 'postratings':
        new_rate = int(request.POST.get('rate'))
        current_record.rating_count += 1
        current_record.rating = round((current_record.rating + new_rate) / 2, 1)
        current_record.best_rating = max(new_rate, current_record.best_rating)
        current_record.worst_rating = min(new_rate, current_record.worst_rating)
        current_record.save()

        Rated_Users.objects.create(user=request.user, record=current_record)

    context = {
        'record': current_record,
        'prev_record': prev_record,
        'next_record': next_record,
        'author': current_record.author,
        'profile': current_record.author.profile,
        'similar_records': two_similar_records,
        'comments': current_record.comments_set.order_by('-date').all(),
        'content': content,
        'is_provided': request.user in current_record.provided_users_set.all(),
        'rated_user': request.user in User.objects.filter(rated_users__record=current_record),
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
        else:
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
def records_by_tags(request: django.http.HttpRequest, tag: str, template: str = 'records_by_tag.html',
                    extra_context: typing.Optional[dict] = None):
    context = {
        'tag': tag,
        'records': Records.objects.filter(tags__tag=tag),
    }

    if extra_context is not None:
        context.update(extra_context)

    return django.shortcuts.render(request, template, context)


# TODO: port on PostgreSQL for more effective search
@el_pagination.decorators.page_template('records_list.html')
def search(request: django.http.HttpRequest, template: str = 'search.html',
           extra_context: typing.Optional[dict] = None):

    search_text = request.GET.get('s')
    found_records = Records.objects.filter(
        Q(title__icontains=search_text) | Q(description__icontains=search_text) |
        Q(content__icontains=search_text) | Q(includes__icontains=search_text) | Q(tags__tag__icontains=search_text)
    )

    context = {
        'search_text': search_text,
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
