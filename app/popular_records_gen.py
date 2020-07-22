from apscheduler.schedulers.background import BackgroundScheduler

from django.template.loader import render_to_string
from app.models import Records

def start():
    scheduler = BackgroundScheduler()
    scheduler.add_job(update, 'interval', days=1)
    scheduler.start()


def update():
    most_popular_records = Records.objects.order_by('rating').all()[:5]
    content = render_to_string('popular_records_template.html', {'record': most_popular_records})

    with open('templates/popular_records.html', 'w') as static_file:
        static_file.write(content)
