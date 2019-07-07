from django.contrib import admin
from app.models import Records
from app.models import Profile

class RecordsAdmin(admin.ModelAdmin):
	list_display = ('title', 'author', 'description', 'includes', 'text', 'price', 'date', 'tags', 'media', 
		'pre_video', 'main_pic')
	list_filter = ('date', 'tags')

	exclude = ('rating', 'best_rating', 'worst_rating', 'rating_count', 'rating_sum', 'rated_users', 'comments_count')


admin.site.register(Records, RecordsAdmin)
admin.site.register(Profile)

