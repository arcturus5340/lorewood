from django.contrib.auth.models import User
from django.db import models
from django.utils import timezone
from django.utils.translation import gettext as _


class ActivationKey(models.Model):
    user = models.ForeignKey(User, on_delete=models.CASCADE)
    activation_key = models.TextField(unique=True)
    is_2step_verification = models.BooleanField(default=False)
    is_email_change = models.BooleanField(default=False)
    is_registration = models.BooleanField(default=False)
    is_remember = models.BooleanField(default=False)
    new_email = models.CharField(max_length=254, null=True, blank=True)

    class Meta:
        db_table = 'app_activation_keys'
        verbose_name = _('Activation Key')
        verbose_name_plural = _('Activation Keys')


class Tags(models.Model):
    id = models.AutoField(primary_key=True)
    tag = models.CharField(max_length=32, unique=True)

    def __str__(self):
        return self.tag

    class Meta:
        verbose_name = _('Tag')
        verbose_name_plural = _('Tags')


class Records(models.Model):
    id = models.AutoField(primary_key=True)
    title = models.CharField(max_length=128)
    content = models.TextField()
    description = models.CharField(max_length=256)
    tags = models.ManyToManyField(Tags)
    main_pic = models.FileField(upload_to='record_src/')
    pre_video = models.FileField(upload_to='record_src/')
    author = models.ForeignKey(User, on_delete=models.CASCADE)
    date = models.DateTimeField(default=timezone.now)
    rating = models.FloatField(default=0.0)
    best_rating = models.IntegerField(default=0)
    rating_count = models.IntegerField(default=0)
    worst_rating = models.IntegerField(default=10)
    includes = models.TextField(default=None, max_length=256, blank=True, null=True)
    price = models.IntegerField()
    sales = models.IntegerField(default=0)

    def __str__(self):
        return self.title

    class Meta:
        verbose_name = _('Record')
        verbose_name_plural = _('Records')


class Header(models.Model):
    id = models.AutoField(primary_key=True)
    record = models.ManyToManyField(Records)
    title = models.CharField(max_length=128)
    _order = models.IntegerField(default=0)

    def __str__(self):
        return self.title

    class Meta:
        db_table = 'app_headers'
        verbose_name = _('Header')
        verbose_name_plural = _('Headers')


class File(models.Model):
    id = models.AutoField(primary_key=True)
    header = models.ManyToManyField(Header)
    src = models.FileField(upload_to='record_src/')
    _order = models.IntegerField(default=0)

    def __str__(self):
        return str(self.src).split('/')[-1]

    class Meta:
        db_table = 'app_files'
        verbose_name = _('File')
        verbose_name_plural = _('Files')


class Comment(models.Model):
    id = models.AutoField(primary_key=True)
    author = models.ForeignKey(User, on_delete=models.CASCADE)
    content = models.TextField()
    date = models.DateTimeField(default=timezone.now)
    record = models.ForeignKey(Records, on_delete=models.CASCADE)

    class Meta:
        db_table = 'app_comments'
        verbose_name = _('Comment')
        verbose_name_plural = _('Comments')


class GlobalSettings(models.Model):
    setting = models.TextField(unique=True)
    value = models.IntegerField()

    class Meta:
        db_table = 'app_global_settings'
        verbose_name = _('Global Settings')
        verbose_name_plural = _('Global Settings')


class Profile(models.Model):
    user = models.OneToOneField(User, on_delete=models.CASCADE)
    avatar = models.FileField(default='/media/avatars/avatar-default.png')
    bio = models.TextField(max_length=500, null=True, blank=True)
    balance = models.IntegerField(default=0)
    is_premium = models.BooleanField(default=False)
    is_verified = models.BooleanField(default=False)
    has_2step_verification = models.BooleanField(default=False)

    class Meta:
        db_table = 'app_profile'
        verbose_name = _('Profile')
        verbose_name_plural = _('Profiles')


class ProvidedUser(models.Model):
    user = models.ForeignKey(User, on_delete=models.CASCADE)
    record = models.ForeignKey(Records, on_delete=models.CASCADE)

    class Meta:
        db_table = 'app_provided_users'
        unique_together = ['user', 'record']
        verbose_name = _('Provided User')
        verbose_name_plural = _('Provided Users')


class RatedUser(models.Model):
    user = models.ForeignKey(User, on_delete=models.CASCADE)
    record = models.ForeignKey(Records, on_delete=models.CASCADE)

    class Meta:
        db_table = 'app_rated_users'
        unique_together = ['user', 'record']
        verbose_name = _('Rated User')
        verbose_name_plural = _('Rated Users')


class Revenue(models.Model):
    date = models.DateField(default=timezone.now)
    income = models.IntegerField(default=0)

    class Meta:
        db_table = 'app_revenue'
        verbose_name = _('Revenue')
        verbose_name_plural = _('Revenue')
