from django.apps import AppConfig


class AppConfig(AppConfig):
    name = 'app'

    def ready(self):
        from app import popular_records_gen
        popular_records_gen.start()
