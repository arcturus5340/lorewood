from django.contrib import admin
from app.models import Records
from app.models import Profile

admin.site.register(Records)
admin.site.register(Profile)

class RecordsAdmin(admin.ModelAdmin):
	list_display = ('title', 'author', 'main_pic', 'description', 'text', 'media', 'date', 'tags',
		'price', 'pre_video', 'includes')
	list_filter = ('name', 'created')
