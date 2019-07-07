from django.contrib.auth.models import User
from django.db.models.signals import post_save
from django.dispatch import receiver
import django.db.models
import datetime


class Records(django.db.models.Model):
    title = django.db.models.CharField(max_length=150)
    author = django.db.models.CharField(default='arcturus5340', max_length=60)
    main_pic = django.db.models.FileField(upload_to="static/record_src/")
    description = django.db.models.TextField(default='', max_length=255)
    text = django.db.models.TextField()
    date = django.db.models.DateField(default=datetime.datetime.now())
    rating = django.db.models.FloatField(default=0.0)
    best_rating = django.db.models.IntegerField(default=1)
    worst_rating = django.db.models.IntegerField(default=10)
    rating_count = django.db.models.IntegerField(default=0)
    rating_sum = django.db.models.IntegerField(default=0)
    rated_users = django.db.models.TextField(default='')
    tags = django.db.models.TextField(default='code, #ihatejs, abinba!', max_length=255)
    comments_count = django.db.models.IntegerField(default=0)
    price = django.db.models.IntegerField(default=0)
    pre_video = django.db.models.FileField(upload_to="static/record_src/")
    includes = django.db.models.TextField(default="-", max_length=255)

    class Meta:
        verbose_name = "Запись" 
        verbose_name_plural = "Записи"

    def __str__(self):
        return self.title


# TODO: automate media paths
class Media(django.db.models.Model):
    record = django.db.models.ForeignKey(Records, on_delete=django.db.models.CASCADE, null=True)
    part_num = django.db.models.IntegerField(default=1)
    title = django.db.models.CharField(max_length=150)
    data = django.db.models.TextField()

    class Meta:
        verbose_name = "Материал" 
        verbose_name_plural = "Материалы"

    def __str__(self):
        return self.title


class Comments(django.db.models.Model):
    author = django.db.models.TextField()
    avatar = django.db.models.TextField(null=True, blank=True, default='/media/avatars/avatar-default.png')
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
