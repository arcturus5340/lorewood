from django.contrib import admin
from app.models import Records
from app.models import Media
import social_django.models

class RecordsAdmin(admin.ModelAdmin):
	list_display = ('title', 'author', 'description', 'includes', 'text', 'price', 'date', 'tags', 
		'pre_video', 'main_pic')
	list_filter = ('date', 'tags')
	search_fields = ('title', 'author', 'description', 'date', 'tags', 'text')

	exclude = ('rating', 'best_rating', 'worst_rating', 'rating_count', 'rating_sum', 'rated_users', 'comments_count')

class MediaAdmin(admin.ModelAdmin):
	list_display = ('record', 'part_num', 'title', 'data')
	list_filter = ('record', 'title')
	search_fields = ('record', 'title')

admin.site.site_header = "Sharewood"
admin.site.register(Records, RecordsAdmin)
admin.site.register(Media, MediaAdmin)
admin.site.unregister(social_django.models.Association)
admin.site.unregister(social_django.models.UserSocialAuth)
admin.site.unregister(social_django.models.Nonce)

