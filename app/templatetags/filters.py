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

class SetVarNode(django.template.Node):

    def __init__(self, var_name, var_value):
        self.var_name = var_name
        self.var_value = var_value

    def render(self, context):
        try:
            value = django.template.Variable(self.var_value).resolve(context)
        except django.template.VariableDoesNotExist:
            value = ""
        context[self.var_name] = value

        return u""

@register.tag(name='set')
def set_var(parser, token):
    """
    {% set some_var = '123' %}
    """
    parts = token.split_contents()
    if len(parts) < 4:
        raise django.template.TemplateSyntaxError("'set' tag must be of the form: {% set <var_name> = <var_value> %}")

    return SetVarNode(parts[1], parts[3])