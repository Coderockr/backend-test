from rest_framework.mixins import CreateModelMixin, ListModelMixin, RetrieveModelMixin
from rest_framework.permissions import AllowAny
from rest_framework.viewsets import GenericViewSet

from events.core.filters import EventFilter
from events.core.models import Event
from events.core.serializers import DetailEventSerializer, ListEventSerializer
from events.core.serializers.event import CreateEventSerializer


class EventView(GenericViewSet, ListModelMixin, RetrieveModelMixin, CreateModelMixin):
    queryset = Event.objects.all()
    filterset_class = EventFilter
    per_action_serializer = {
        "list": ListEventSerializer,
        "retrieve": DetailEventSerializer,
        "create": CreateEventSerializer,
    }

    def get_serializer_class(self):
        return self.per_action_serializer.get(self.action)

    def get_permissions(self):
        return [AllowAny()] if self.action in ["list", "retrieve"] else super().get_permissions()
