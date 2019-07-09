from django.contrib import admin
from app.models import Records
from app.models import Media
from app.models import Premium
from app.models import Profile
import social_django.models

class RecordsAdmin(admin.ModelAdmin):
	list_display = ('title', 'author', 'description', 'includes', 'text', 'price', 'date', 'tags', 
		'pre_video', 'main_pic')
	list_filter = ('date', 'tags')
	search_fields = ('title', 'author', 'description', 'date', 'tags', 'text')

	exclude = ('rating', 'best_rating', 'worst_rating', 'rating_count', 'rating_sum', 'rated_users', 'comments_count', 'sales')

class MediaAdmin(admin.ModelAdmin):
	list_display = ('record', 'title', 'file1', 'file2', 'file3', 'file4', 'file5', 'file6'
		, 'file7', 'file8', 'file9', 'file10', 'file11', 'file12', 'file13', 'file14', 'file15', 'file16',
        'file17', 'file18', 'file19', 'file20', 'file21', 'file22', 'file23', 'file24', 'file25')
	list_filter = ('record', 'title')
	search_fields = ('record', 'title')

class PremiumAdmin(admin.ModelAdmin):
	list_display = ('id', 'premium_cost')

class ProfileAdmin(admin.ModelAdmin):
	list_display = ('user', 'avatar', 'bio', 'balance', 'is_premium')
	search_fields = ('user', 'bio')

admin.site.site_header = "Sharewood"
admin.site.register(Records, RecordsAdmin)
admin.site.register(Profile, ProfileAdmin)
admin.site.register(Premium, PremiumAdmin)
admin.site.register(Media, MediaAdmin)
admin.site.unregister(social_django.models.Association)
admin.site.unregister(social_django.models.UserSocialAuth)
admin.site.unregister(social_django.models.Nonce)

