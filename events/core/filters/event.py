from django_filters import FilterSet
from django_filters.filters import BooleanFilter, CharFilter

from events.core.models import Event


class EventFilter(FilterSet):
    """
    Filter events by the following parameters:
        - date
        - place (iexact)
    """

    place = CharFilter(lookup_expr="iexact")

    class Meta:
        model = Event
        fields = ["date", "place"]


class MyEventsFilter(FilterSet):
    """
    Filter events by the following parameters:
        - owner (boolean)
        - participating (boolean)
    """

    owner = BooleanFilter(method="owner_filter")
    participating = BooleanFilter(method="participating_filter")

    def owner_filter(self, queryset, _, value):
        return queryset.own_events(self.request.user) if value else queryset

    def participating_filter(self, queryset, _, value):
        return queryset.participating_events(self.request.user) if value else queryset

    class Meta:
        model = Event
        fields = ["owner"]
