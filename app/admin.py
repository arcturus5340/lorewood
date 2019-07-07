from django.contrib import admin
from app.models import Records
import social_django.models

class RecordsAdmin(admin.ModelAdmin):
	list_display = ('title', 'author', 'description', 'includes', 'text', 'price', 'date', 'tags', 'media', 
		'pre_video', 'main_pic')
	list_display_links = ('date', 'media')
	list_filter = ('date', 'tags')
	list_editable = ('title', 'description', 'includes', 'price', 'tags', 'main_pic')
	search_fields = ('title', 'author', 'description', 'date', 'tags', 'text')

	exclude = ('rating', 'best_rating', 'worst_rating', 'rating_count', 'rating_sum', 'rated_users', 'comments_count')


admin.site.register(Records, RecordsAdmin)
admin.site.unregister(social_django.models.Association)
admin.site.unregister(social_django.models.UserSocialAuth)
admin.site.unregister(social_django.models.Nonce)

