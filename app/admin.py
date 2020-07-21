from django import forms
from django.contrib import admin

from app import models

from ckeditor_uploader.widgets import CKEditorUploadingWidget
import social_django.models


@admin.register(models.ActivationKey)
class ActivationKeyAdmin(admin.ModelAdmin):
    empty_value_display = '-empty-'
    readonly_fields = (
        'activation_key', 'is_2step_verification', 'is_email_change', 'is_registration', 'is_remember',
    )
    list_display = (
        'activation_key',  'new_email', 'is_2step_verification', 'is_email_change', 'is_registration', 'is_remember',
    )
    list_filter = (
        'new_email', 'is_2step_verification', 'is_email_change', 'is_registration', 'is_remember',
    )


@admin.register(models.Tags)
class Tags(admin.ModelAdmin):
    list_display = (
        'tag',
    )


class RecordsForm(forms.ModelForm):
    description = forms.CharField(widget=CKEditorUploadingWidget())
    content = forms.CharField(widget=CKEditorUploadingWidget())
    includes = forms.CharField(widget=CKEditorUploadingWidget())


@admin.register(models.Records)
class Records(admin.ModelAdmin):
    date_hierarchy = 'date'
    form = RecordsForm
    list_display = (
        'title', 'description', 'content', 'author', 'rating', 'price', 'date',
    )
    readonly_fields = (
        'rating', 'best_rating', 'worst_rating', 'rating_count', 'sales',
    )


@admin.register(models.Header)
class Header(admin.ModelAdmin):
    list_display = (
        'title', 'record', '_order',
    )


@admin.register(models.File)
class File(admin.ModelAdmin):
    list_display = (
        'src', 'header', '_order',
    )


class CommentsForm(forms.ModelForm):
    content = forms.CharField(widget=CKEditorUploadingWidget())


@admin.register(models.Comment)
class Comment(admin.ModelAdmin):
    date_hierarchy = 'date'
    form = CommentsForm
    list_display = (
        'author', 'record', 'content', 'date',
    )


@admin.register(models.GlobalSettings)
class GlobalSettings(admin.ModelAdmin):
    readonly_fields = (
        'setting',
    )
    list_display = (
        'setting', 'value',
    )


@admin.register(models.Profile)
class Profile(admin.ModelAdmin):
    empty_value_display = '-empty-'
    list_display = (
        'user', 'bio', 'is_premium', 'is_verified', 'has_2step_verification', 'balance',
    )
    list_filter = (
        'user', 'bio',
    )


@admin.register(models.Revenue)
class Revenue(admin.ModelAdmin):
    date_hierarchy = 'date'
    list_display = (
        'date', 'income',
    )
    readonly_fields = (
        'date', 'income',
    )


admin.site.site_header = "Sharewood"
admin.site.unregister(social_django.models.Association)
admin.site.unregister(social_django.models.UserSocialAuth)
admin.site.unregister(social_django.models.Nonce)
