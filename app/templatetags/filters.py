import django.template


register = django.template.Library()


@register.filter
def for_range(value, start_index=0):
    return range(start_index, value+start_index)


@register.filter(name='split')
def split_filter(value, arg):
    return value.split(arg)


@register.filter(name='strftime')
def strftime(date, template):
    return date.strftime(template)