import django.template


register = django.template.Library()


@register.filter(name='strftime')
def strftime(date, template):
    return date.strftime(template)
