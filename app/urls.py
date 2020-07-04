from django.urls import path
from .views import api, auth, cabinet, menu

urlpatterns = [
    path('', menu.index, name='index'),
    path('login/', auth.login, name='login'),
    path('remember/', auth.remember, name='remember'),

    path('logout/', auth.logout, name='logout'),
    path('register/', auth.register, name='register'),
    path('advertising/', menu.advertising, name='advertising'),
    path('donations/', menu.donations, name='donations'),
    path('info/', menu.info, name='info'),
    path('regulations/', menu.regulations, name='regulations'),
    path('rightholder/', menu.rightholder, name='rightholder'),

    path('user/<str:username>/activate/<str:activation_key>', auth.activate_account, name='activate_account'),
    path('user/<str:username>/verificate/<str:activation_key>', auth.verificate_login, name='verificate_login'),
    path('save-personal-data/', cabinet.save_personal_data, name="save_personal_data"),
    path('user/<str:username>/cabinet/<str:list>', cabinet.cabinet, name="cabinet"),
    path('user/<str:username>/remember/<str:activation_key>', cabinet.password_change_view, name='password_change_view'),
    path('user/<str:username>/change-password/', cabinet.change_password, name="change_password"),
    path('change-cabinet-password/', cabinet.change_cabinet_password, name="change_cabinet_password"),
    path('change-email/', auth.change_email, name="change_email"),
    path('user/<str:username>/change-email/<str:activation_key>',
         cabinet.change_email_confirm,
         name="change_email_confirm"),

    path('r<int:record_id>/', menu.record, name='record'),
    path('tag/<str:tag>/', menu.records_by_tags, name='records_by_text'),
    path('search', menu.search, name='search'),
    path('api/<str:data>', api.api, name='api-data'),
    path('statistics/', api.statistics),
    path('r<int:record_id>/buy/', menu.buy, name='buy'),
    path('buy_premium/', cabinet.buy_premium, name='buy_premium'),
    path('two_verif_on/', cabinet.two_verif_on, name='two_verif_on'),
    path('two_verif_off/', cabinet.two_verif_off, name='two_verif_off'),
]
