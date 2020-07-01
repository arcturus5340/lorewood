from django import forms
from django.contrib import admin

from app.models import Media, Records, Premium, Profile

from ckeditor_uploader.widgets import CKEditorUploadingWidget
import social_django.models


class MediaAdmin(admin.ModelAdmin):
	list_display = (
		'record',
		'title',
		*['file{}'.format(i) for i in range(1, 26)],
	)
	list_filter = search_fields = (
		'record',
		'title',
	)


class PremiumAdmin(admin.ModelAdmin):
	list_display = (
		'id',
		'premium_cost',
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
		'text',
		'price',
		'date',
		'tags',
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
		'comments_count',
		'sales',
	)


admin.site.site_header = "Sharewood"
admin.site.register(Media, MediaAdmin)
admin.site.register(Premium, PremiumAdmin)
admin.site.register(Profile, ProfileAdmin)
admin.site.register(Records, RecordsAdmin)
admin.site.unregister(social_django.models.Association)
admin.site.unregister(social_django.models.UserSocialAuth)
admin.site.unregister(social_django.models.Nonce)
