from django_filters import CharFilter, FilterSet

from events.core.models import Event


class EventFilter(FilterSet):
    region = CharFilter(lookup_expr="iexact")

    class Meta:
        model = Event
        fields = ["date", "region"]
