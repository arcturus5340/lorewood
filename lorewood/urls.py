"""lorewood URL Configuration

The `urlpatterns` list routes URLs to views. For more information please see:
    https://docs.djangoproject.com/en/2.2/topics/http/urls/
Examples:
Function views
    1. Add an import:  from my_app import views
    2. Add a URL to urlpatterns:  path('', views.home, name='home')
Class-based views
    1. Add an import:  from other_app.views import Home
    2. Add a URL to urlpatterns:  path('', Home.as_view(), name='home')
Including another URLconf
    1. Import the include() function: from django.urls import include, path
    2. Add a URL to urlpatterns:  path('blog/', include('blog.urls'))
"""

import django.contrib.admin
import django.urls
import django.conf
import django.conf.urls.static
import django.contrib.auth.views

urlpatterns = [
    django.urls.re_path('', django.urls.include('app.urls')),
    django.urls.path('admin/', django.contrib.admin.site.urls),
    django.urls.path('social/', django.urls.include('social_django.urls')),
    django.urls.path('ckeditor/', django.urls.include('ckeditor_uploader.urls')),
] + django.conf.urls.static.static(django.conf.settings.STATIC_URL, document_root=django.conf.settings.STATIC_ROOT)

if django.conf.settings.DEBUG:
    urlpatterns += [
        django.urls.re_path(r'^media/(?P<path>.*)$', django.views.static.serve, {
            'document_root': django.conf.settings.MEDIA_ROOT,
        }),
    ]