# Generated by Django 2.2.2 on 2019-07-09 15:20

import datetime
from django.db import migrations, models


class Migration(migrations.Migration):

    dependencies = [
        ('app', '0051_auto_20190709_1433'),
    ]

    operations = [
        migrations.AlterField(
            model_name='comments',
            name='date',
            field=models.DateField(default=datetime.datetime(2019, 7, 9, 15, 20, 24, 256647)),
        ),
        migrations.AlterField(
            model_name='records',
            name='date',
            field=models.DateField(default=datetime.datetime(2019, 7, 9, 15, 20, 24, 255095)),
        ),
        migrations.AlterField(
            model_name='revenue',
            name='date',
            field=models.DateField(default=datetime.datetime(2019, 7, 9, 15, 20, 24, 257031)),
        ),
    ]
