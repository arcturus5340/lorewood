import django.template


register = django.template.Library()


@register.filter(name='split')
def split_filter(value, arg):
    return value.split(arg)


@register.filter(name='strftime')
def strftime(date, template):
    return date.strftime(template)
