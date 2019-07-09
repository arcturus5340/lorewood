from django.contrib.auth.models import User
from django.db.models.signals import post_save
from django.dispatch import receiver
import django.db.models
import datetime


class Records(django.db.models.Model):
    path = "record_src/"
    title = django.db.models.TextField()
    text = django.db.models.TextField()
    description = django.db.models.CharField(max_length=300)
    rating = django.db.models.FloatField(default=0.0)
    main_pic = django.db.models.FileField(upload_to=path)
    author = django.db.models.CharField(max_length=30)
    tags = django.db.models.TextField()
    comments_count = django.db.models.IntegerField(default=0)
    best_rating = django.db.models.IntegerField(default=0)
    rating_count = django.db.models.IntegerField(default=0)
    worst_rating = django.db.models.IntegerField(default=10)
    rating_sum = django.db.models.IntegerField(default=0)
    rated_users = django.db.models.TextField(default='')
    includes = django.db.models.TextField(default='-', max_length=300)
    pre_video = django.db.models.FileField(upload_to=path)
    price = django.db.models.IntegerField()
    sales = django.db.models.IntegerField(default=0)
    date = django.db.models.DateField(default=datetime.datetime.now())
    provided_users = django.db.models.TextField(default='')

    class Meta:
        verbose_name = "Запись" 
        verbose_name_plural = "Записи"

    def __str__(self):
        return self.title


# TODO: automate media paths
class Media(django.db.models.Model):
    record = django.db.models.ForeignKey(Records, on_delete=django.db.models.CASCADE, null=True)
    path = "record_src/"
    part_num = django.db.models.IntegerField(default=1)
    title = django.db.models.CharField(max_length=150)

    file1 = django.db.models.FileField(upload_to=path, null=True, blank=True)
    file2 = django.db.models.FileField(upload_to=path, null=True, blank=True)
    file3 = django.db.models.FileField(upload_to=path, null=True, blank=True)
    file4 = django.db.models.FileField(upload_to=path, null=True, blank=True)
    file5 = django.db.models.FileField(upload_to=path, null=True, blank=True)
    file6 = django.db.models.FileField(upload_to=path, null=True, blank=True)
    file7 = django.db.models.FileField(upload_to=path, null=True, blank=True)
    file8 = django.db.models.FileField(upload_to=path, null=True, blank=True)
    file9 = django.db.models.FileField(upload_to=path, null=True, blank=True)
    file10 = django.db.models.FileField(upload_to=path, null=True, blank=True)
    file11 = django.db.models.FileField(upload_to=path, null=True, blank=True)
    file12 = django.db.models.FileField(upload_to=path, null=True, blank=True)
    file13 = django.db.models.FileField(upload_to=path, null=True, blank=True)
    file14 = django.db.models.FileField(upload_to=path, null=True, blank=True)
    file15 = django.db.models.FileField(upload_to=path, null=True, blank=True)
    file16 = django.db.models.FileField(upload_to=path, null=True, blank=True)
    file17 = django.db.models.FileField(upload_to=path, null=True, blank=True)
    file18 = django.db.models.FileField(upload_to=path, null=True, blank=True)
    file19 = django.db.models.FileField(upload_to=path, null=True, blank=True)
    file20 = django.db.models.FileField(upload_to=path, null=True, blank=True)
    file21 = django.db.models.FileField(upload_to=path, null=True, blank=True)
    file22 = django.db.models.FileField(upload_to=path, null=True, blank=True)
    file23 = django.db.models.FileField(upload_to=path, null=True, blank=True)
    file24 = django.db.models.FileField(upload_to=path, null=True, blank=True)
    file25 = django.db.models.FileField(upload_to=path, null=True, blank=True)

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


class Revenue(django.db.models.Model):
    date = django.db.models.DateField(default=datetime.datetime.now())
    income = django.db.models.IntegerField(default=0)
    # visitors = django.db.models.IntegerField(default=0)


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
