from django_filters import CharFilter, FilterSet

from events.core.models import Event


class EventFilter(FilterSet):
    place = CharFilter(lookup_expr="iexact")

    class Meta:
        model = Event
        fields = ["date", "place"]
