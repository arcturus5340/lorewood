import django.apps


class AppConfig(django.apps.AppConfig):
    name = 'app'

    def ready(self):
        from app import popular_records_gen
        popular_records_gen.start()
