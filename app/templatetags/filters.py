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
    sdate = date.strftime(template).split()
    month = int(sdate[1])
    if month == 1:
        sdate[1] = 'Января'
    elif month == 2:
        sdate[1] = 'Февраля'
    elif month == 3:
        sdate[1] = 'Марта'
    elif month == 4:
        sdate[1] = 'Апреля'
    elif month == 5:
        sdate[1] = 'Мая'
    elif month == 6:
        sdate[1] = 'Июня'
    elif month == 7:
        sdate[1] = 'Июля'
    elif month == 8:
        sdate[1] = 'Августа'
    elif month == 9:
        sdate[1] = 'Сентября'
    elif month == 10:
        sdate[1] = 'Октябрь'
    elif month == 11:
        sdate[1] = 'Ноябрь'
    elif month == 12:
        sdate[1] = 'Декабрь'
    return " ".join(sdate)


@register.filter(name='zip')
def zip_lists(a, b):
  return zip(a, b)