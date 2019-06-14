from django.http import HttpResponse
from django.template import loader


def index(request):
    if request.path == '/': request.path = '/index.html'
    template = loader.get_template('../templates{}'.format(request.path))
    return HttpResponse(template.render())
