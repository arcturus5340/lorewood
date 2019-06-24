from django.contrib import admin
from app.models import UserActivation
from app.models import Records
from app.models import Profile
from app.models import UserEmail

admin.site.register(UserActivation)
admin.site.register(Records)
admin.site.register(Profile)
admin.site.register(UserEmail)
# Register your models here.
