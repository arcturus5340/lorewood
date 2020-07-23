from django.urls import path
from .views import confirmation, menu, statistics, auth, cabinet

urlpatterns = [
    path('', menu.index),
    path('info/', menu.info),
    path('regulations/', menu.regulations),
    path('advertising/', menu.advertising),
    path('rightholder/', menu.rightholder),
    path('donations/', menu.donations),

    path('r<int:record_id>/', menu.record),
    path('r<int:record_id>/buy/', cabinet.buy),

    path('tag/<str:tag>/', menu.records_by_tags),
    path('search/', menu.search),
    path('statistics/', statistics.statistics),

    path('login/', auth.login),
    path('remember/', auth.remember),
    path('change-email/', auth.change_email),
    path('register/', auth.register),
    path('logout/', auth.logout),

    path('user/<str:username>/activate/<str:activation_key>', confirmation.activate_setting),

    path('user/<str:username>/cabinet', cabinet.cabinet),
    path('save-personal-data/', cabinet.save_personal_data),
    path('user/<str:username>/change-password/', cabinet.change_password),
    path('change-password/', cabinet.change_password),

    path('buy_premium/', cabinet.buy_premium),
    path('two_verif_on/', cabinet.two_verif_on),
    path('two_verif_off/', cabinet.two_verif_off),
]
