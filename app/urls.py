from django.conf.urls import url
from . import views
from django.urls import include, path, re_path


urlpatterns = [
    path('', views.index, name='index'),
    path('login/', views.login, name='login'),
    path('logout/', views.logout, name='logout'),
    path('register/', views.register, name='register'),
    path('advertising/', views.advertising, name='advertising'),
    path('donations/', views.donations, name='donations'),
    path('info/', views.info, name='info'),
    path('regulations/', views.regulations, name='regulations'),
    path('rightholder/', views.rightholder, name='rightholder'),
    path('nazvanie-zapisi-8-nazvanie-zapisi-8/', views.nazvanie_zapisi_8_nazvanie_zapisi_8, name='advertising'),
    re_path(r'r.*/', views.record, name='advertising'),
]