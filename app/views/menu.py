from django.contrib.auth.models import User
from django.db.models import Q
from django.http import HttpRequest
from django.http.response import JsonResponse
from django.shortcuts import render

import el_pagination.decorators

import datetime
import logging
import mimetypes
import random
from typing import Optional

from app.models import Comments, Records, Rated_Users, Provided_Users, Revenue

logger = logging.getLogger('app')


@el_pagination.decorators.page_template('records_list.html')
def index(request: HttpRequest, template: str = 'index.html', extra_context: Optional[dict] = None):
    records = Records.objects.order_by('-rating')

    context = {
        'records': records[4:],
        'last_records': records[:4],
    }

    if extra_context is not None:
        context.update(extra_context)

    return render(request, template, context)


# TODO: output date and time for client time zone
@el_pagination.decorators.page_template('comments_list.html')
def record(request: HttpRequest, record_id: int, template: str = "record.html", extra_context: Optional[dict] = None):
    records_qs = Records.objects.all()
    current_record = records_qs.get(id=record_id)
    prev_record = records_qs.filter(pk__gt=current_record.pk).order_by('-pk').first()
    next_record = records_qs.filter(pk__gt=current_record.pk).order_by('pk').first()

    content = dict()
    for header in current_record.headers_set.all():
        content[header.title] = list()
        for file in header.files_set.all():
            file_type, _ = mimetypes.guess_type(file.src.name)
            if not file_type:
                continue
            elif file_type.split('/')[0] == 'video':
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

    # TODO: Migrate on AJAX for comment system
    if request.POST.get('add_comment'):
        Comments.objects.create(
            author=request.user,
            content=request.POST.get('add_comment'),
            date=datetime.datetime.now(),
            record=current_record
        )

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
        'similar_records': two_similar_records,
        'comments': current_record.comments_set.order_by('-date').all(),
        'content': content,
        'is_provided': request.user in current_record.provided_users_set.all(),
        'rated_user': request.user in User.objects.filter(rated_users__record=current_record),
    }

    if extra_context is not None:
        context.update(extra_context)

    return render(request, template, context)


def buy(request: HttpRequest, record_id: int):
    user = request.user
    if not user.profile.is_verified:
        logger.info('Record purchase fail: Verification required')
        return JsonResponse({
            'status': 'fail',
            'message': _('Verify your account first'),
        })

    current_record = Records.objects.get(id=record_id)

    if Provided_Users.objects.filter(user=user, record=current_record).exists():
        logger.info('Record purchase fail: User is provided with this record')
        return JsonResponse({
            'status': 'fail',
            'message': _('You have already bought this record'),
        })

    if (user.profile.balance - current_record.price) < 0:
        logger.info('Record purchase fail: User balance is not enough')
        return JsonResponse({
            'status': 'fail',
            'message': _('There are not enough funds on your balance'),
        })

    user.profile.balance -= current_record.price
    user.profile.save()

    Provided_Users.objects.create(user=user, record=current_record)

    obj, _ = Revenue.objects.get_or_create(date=datetime.date.today(), defaults={'income': 0})
    obj.income += current_record.price
    obj.save()

    current_record.sales += 1
    current_record.save()

    return JsonResponse({
        'status': 'ok',
    })


@el_pagination.decorators.page_template('records_list.html')
def records_by_tags(request: HttpRequest, tag: str, template: str = 'records_by_tag.html', extra_context: Optional[dict] = None):
    context = {
        'tag': tag,
        'records': Records.objects.filter(tags__tag=tag),
    }

    if extra_context is not None:
        context.update(extra_context)

    return render(request, template, context)


# TODO: port on PostgreSQL for more effective search
@el_pagination.decorators.page_template('records_list.html')
def search(request: HttpRequest, template: str = 'search.html', extra_context: Optional[dict] = None):
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

    return render(request, template, context)


def advertising(request: HttpRequest):
    return render(request, 'advertising.html')


def donations(request: HttpRequest):
    return render(request, 'donations.html')


def info(request: HttpRequest):
    return render(request, 'info.html')


def regulations(request: HttpRequest):
    return render(request, 'regulations.html')


def rightholder(request: HttpRequest):
    return render(request, 'rightholder.html')
