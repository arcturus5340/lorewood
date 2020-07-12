from django.db import models
from django.utils import timezone

from django.contrib.auth.models import User


# TODO: abandon the field 'rating' in order to preserve the redundancy property
class Records(models.Model):
    id = models.AutoField(primary_key=True)
    title = models.CharField(max_length=128)
    content = models.TextField()
    description = models.CharField(max_length=256)
    main_pic = models.FileField(upload_to='record_src/')
    pre_video = models.FileField(upload_to='record_src/')
    author = models.ForeignKey(User, on_delete=models.CASCADE)
    date = models.DateField(default=timezone.now())
    rating = models.FloatField(default=0.0)
    best_rating = models.IntegerField(default=0)
    rating_count = models.IntegerField(default=0)
    worst_rating = models.IntegerField(default=10)
    rating_sum = models.IntegerField(default=0)
    includes = models.TextField(default='-', max_length=256)
    price = models.IntegerField()
    sales = models.IntegerField(default=0)

    class Meta:
        verbose_name = "Запись" 
        verbose_name_plural = "Записи"


class Provided_Users(models.Model):
    user = models.ForeignKey(User, on_delete=models.CASCADE)
    record = models.ForeignKey(Records, on_delete=models.CASCADE)


class Rated_Users(models.Model):
    user = models.ForeignKey(User, on_delete=models.CASCADE)
    record = models.ForeignKey(Records, on_delete=models.CASCADE)


class Headers(models.Model):
    id = models.AutoField(primary_key=True)
    record = models.ForeignKey(Records, on_delete=models.CASCADE)
    title = models.CharField(max_length=128)
    _order = models.IntegerField(default=0)


class Files(models.Model):
    id = models.AutoField(primary_key=True)
    header = models.ForeignKey(Headers, on_delete=models.CASCADE)
    src = models.FileField(upload_to='record_src/')
    _order = models.IntegerField(default=0)

    class Meta:
        verbose_name = "Материал" 
        verbose_name_plural = "Материалы"


class Tags(models.Model):
    id = models.ManyToManyField(Records)
    tag = models.CharField(max_length=32, unique=True)


class Comments(models.Model):
    id = models.AutoField(primary_key=True)
    author = models.ForeignKey(User, on_delete=models.CASCADE)
    content = models.TextField()
    date = models.DateField(default=timezone.now())
    record = models.ForeignKey(Records, on_delete=models.CASCADE)


class Revenue(models.Model):
    date = models.DateField(default=timezone.now())
    income = models.IntegerField(default=0)


class Activation(models.Model):
    user = models.ForeignKey(User, on_delete=models.CASCADE)
    activation_key = models.TextField(unique=True)
    new_email = models.CharField(max_length=254, null=True, blank=True)


class Profile(models.Model):
    user = models.OneToOneField(User, on_delete=models.CASCADE)
    avatar = models.FileField(default='/media/avatars/avatar-default.png')
    bio = models.TextField(max_length=500, null=True, blank=True)
    balance = models.IntegerField(default=0)
    is_premium = models.BooleanField(default=False)
    has_2stepverif = models.BooleanField(default=False)

    class Meta:
        verbose_name = "Профиль" 
        verbose_name_plural = "Профили"
