import django.db.models
import datetime

# Create your models here.

class Records(django.db.models.Model):
    title = django.db.models.TextField()
    main_pic = django.db.models.FilePathField(default=None)
    description = django.db.models.TextField(default='')
    text = django.db.models.TextField()
#    date = django.db.models.DateField(auto_now_add=True)
    rating = django.db.models.FloatField(default=0.0)

class UserActivationManager(django.db.models.Manager):
	def create_user_key(self, username, activation_key):
		user_key = self.create(username=username, activation_key=activation_key)
		return user_key

class UserActivation(django.db.models.Model):
	username = django.db.models.TextField()
	activation_key = django.db.models.TextField()
	
	objects = UserActivationManager()
