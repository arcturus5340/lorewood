from django.contrib.auth.models import User
from django.db.models.signals import post_save
from django.dispatch import receiver
import django.db.models
import datetime


class Records(django.db.models.Model):
    title = django.db.models.TextField()
    author = django.db.models.TextField(default='arcturus5340')
    main_pic = django.db.models.FilePathField(default=None)
    description = django.db.models.TextField(default='')
    text = django.db.models.TextField()
    media = django.db.models.TextField(default='1, 2, 3, 4')
    date = django.db.models.DateField(default=datetime.datetime.now())
    rating = django.db.models.FloatField(default=0.0)
    best_rating = django.db.models.IntegerField(default=1)
    worst_rating = django.db.models.IntegerField(default=10)
    rating_count = django.db.models.IntegerField(default=0)
    rating_sum = django.db.models.IntegerField(default=0)
    rated_users = django.db.models.TextField(default='')
    tags = django.db.models.TextField(default='code, #ihatejs, abinba!')
    comments_count = django.db.models.IntegerField(default=0)
    price = django.db.models.IntegerField(default=0)
    pre_video = django.db.models.TextField(default="/static/")
    includes = django.db.models.TextField(default="-")


# TODO: automate media paths
class Media(django.db.models.Model):
    title = django.db.models.TextField()
    data = django.db.models.TextField()


class Comments(django.db.models.Model):
    author = django.db.models.TextField()
    text = django.db.models.TextField()
    date = django.db.models.DateField(default=datetime.datetime.now())
    record_id = django.db.models.IntegerField(default=1)


class UserActivationManager(django.db.models.Manager):
    def create_user_key(self, username, activation_key):
        user_key = self.create(username=username, activation_key=activation_key)
        return user_key


class UserActivation(django.db.models.Model):
    username = django.db.models.TextField()
    activation_key = django.db.models.TextField()
    objects = UserActivationManager()


class UserEmailManager(django.db.models.Manager):
    def create_user_key(self, username, activation_key, email):
        user_key = self.create(username=username, activation_key=activation_key, email=email)
        return user_key


class UserEmail(django.db.models.Model):
    username = django.db.models.TextField()
    activation_key = django.db.models.TextField()
    email = django.db.models.TextField()
    objects = UserEmailManager()


class Profile(django.db.models.Model):
    user = django.db.models.OneToOneField(User, on_delete=django.db.models.CASCADE)
    avatar = django.db.models.TextField(null=True, blank=True, default='/media/avatars/avatar-default.png')
    bio = django.db.models.TextField(max_length=500, blank=True)
    balance = django.db.models.IntegerField(default=0)


@receiver(post_save, sender=User)
def create_user_profile(sender, instance, created, **kwargs):
    if created:
        Profile.objects.create(user=instance)


@receiver(post_save, sender=User)
def save_user_profile(sender, instance, **kwargs):
    instance.profile.save()
