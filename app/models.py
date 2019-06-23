import django.db.models
import datetime


class Records(django.db.models.Model):
    title = django.db.models.TextField()
    author = django.db.models.TextField(default='arcturus5340')
    main_pic = django.db.models.FilePathField(default=None)
    description = django.db.models.TextField(default='')
    text = django.db.models.TextField()
    media = django.db.models.TextField(default='/static/record_src/r1/media/01. Balls To The Wall.mp3')
    date = django.db.models.DateField(default=datetime.datetime.now())
    rating = django.db.models.FloatField(default=0.0)
    tags = django.db.models.TextField(default='code, #ihatejs, abinba!')


class UserActivationManager(django.db.models.Manager):
    def create_user_key(self, username, activation_key):
        user_key = self.create(username=username, activation_key=activation_key)
        return user_key


class UserActivation(django.db.models.Model):
    username = django.db.models.TextField()
    activation_key = django.db.models.TextField()
    objects = UserActivationManager()
