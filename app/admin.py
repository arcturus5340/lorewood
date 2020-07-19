from django import forms
from django.contrib import admin

from app.models import Files, Headers, Records, Profile

from ckeditor_uploader.widgets import CKEditorUploadingWidget
import social_django.models


class FilesAdmin(admin.ModelAdmin):
	list_filter = search_fields = list_display = (
		'header',
		'src',
	)

class HeadersAdmin(admin.ModelAdmin):
	list_filter = search_fields = list_display = (
		'title',
		'record',
	)


class ProfileAdmin(admin.ModelAdmin):
	list_display = (
		'user',
		'avatar',
		'bio',
		'balance',
		'is_premium',
	)
	search_fields = (
		'user',
		'bio',
	)


class RecordsAdminForm(forms.ModelForm):
	text = forms.CharField(widget=CKEditorUploadingWidget())
	description = forms.CharField(widget=CKEditorUploadingWidget())
	includes = forms.CharField(widget=CKEditorUploadingWidget())


class RecordsAdmin(admin.ModelAdmin):
	form = RecordsAdminForm
	list_display = (
		'title',
		'author',
		'description',
		'includes',
		'content',
		'price',
		'date',
		'pre_video',
		'main_pic',
	)
	list_filter = (
		'date',
		'tags',
	)
	search_fields = (
		'title',
		'author',
		'description',
		'date',
		'tags',
		'text',
	)
	exclude = (
		'rating',
		'best_rating',
		'worst_rating',
		'rating_count',
		'rating_sum',
		'rated_users',
		'sales',
	)


admin.site.site_header = "Sharewood"
admin.site.register(Files, FilesAdmin)
admin.site.register(Headers, HeadersAdmin)
admin.site.register(Profile, ProfileAdmin)
admin.site.register(Records, RecordsAdmin)
admin.site.unregister(social_django.models.Association)
admin.site.unregister(social_django.models.UserSocialAuth)
admin.site.unregister(social_django.models.Nonce)
