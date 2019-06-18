import django.template


register = django.template.Library()

@register.filter
def for_range(value, start_index=0):
    return range(start_index, value+start_index)