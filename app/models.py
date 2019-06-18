import django.db.models
import datetime

# Create your models here.

class Records(django.db.models.Model):
    title = django.db.models.TextField()
    description = django.db.models.TextField(default='')
    text = django.db.models.TextField()
#    date = django.db.models.DateField(auto_now_add=True)
    rating = django.db.models.FloatField(default=0.0)
