from rest_framework.mixins import ListModelMixin, RetrieveModelMixin
from rest_framework.permissions import AllowAny
from rest_framework.viewsets import GenericViewSet

from events.core.filters import EventFilter
from events.core.models import Event
from events.core.serializers import DetailEventSerializer, ListEventSerializer


class EventView(GenericViewSet, ListModelMixin, RetrieveModelMixin):
    queryset = Event.objects.all()
    filterset_class = EventFilter
    permission_classes = [AllowAny]
    per_action_serializer = {
        "list": ListEventSerializer,
        "retrieve": DetailEventSerializer,
    }

    def get_serializer_class(self):
        return self.per_action_serializer.get(self.action)
