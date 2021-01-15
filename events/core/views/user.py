from rest_framework.decorators import action
from rest_framework.permissions import IsAuthenticated
from rest_framework.viewsets import GenericViewSet

from events.core.filters import MyEventsFilter
from events.core.models import Event
from events.core.serializers import ListEventSerializer


class UserViewSet(GenericViewSet):
    filterset_class = MyEventsFilter

    @action(detail=False, permission_classes=[IsAuthenticated])
    def my_events(self, _):
        queryset = Event.objects.get_own_or_participating_events(self.request.user)
        filtered_queryset = self.filter_queryset(queryset)
        paginated_data = self.paginate_queryset(filtered_queryset)
        serializer = ListEventSerializer(instance=paginated_data, many=True)

        return self.get_paginated_response(serializer.data)
