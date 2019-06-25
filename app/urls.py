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
    path('user/<str:username>/activate/<str:activation_key>', views.activate_account, name='activate_account'),
    # Форма для изменения пароля 
    path('user/<str:username>/remember/<str:activation_key>', views.password_change_view, name='password_change_view'),
    path('remember/', views.remember, name='remember'),
    # Функция изменение пароля
    path('user/<str:username>/change-password/', views.change_password, name="change_password"),
    path('user/<str:username>/cabinet', views.cabinet, name="cabinet"),
    
    path('change-cabinet-password/', views.change_cabinet_password, name="change_cabinet_password"),
    path('change-email/', views.change_email, name="change_email"),
    path('user/<str:username>/change-email/<str:activation_key>', views.change_email_confirm, name="change_email_confirm"),
    path('save-personal-data/', views.save_personal_data, name="save_personal_data"),

    path('r<int:record_id>/', views.record, name='record'),
    path('tag/<str:tag>/', views.records_by_tags, name='records_by_text'),
    path('search', views.search, name='search'),
]