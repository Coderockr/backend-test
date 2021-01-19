from rest_framework.permissions import DjangoModelPermissions
from rest_framework.viewsets import ModelViewSet

from events.core.filters import EventFilter
from events.core.models import Event
from events.core.permissions import CanChangeOrDestroyEvent, ReadOnly
from events.core.serializers import DetailEventSerializer, ListEventSerializer
from events.core.serializers.event import CreateEventSerializer, UpdateEventSerializer


class EventViewSet(ModelViewSet):
    """
    List, Retrieve, Create, Update and Destroy events
    """

    queryset = Event.objects.all()
    filterset_class = EventFilter
    per_action_permission = {
        "list": [ReadOnly],
        "retrieve": [ReadOnly],
        "create": [DjangoModelPermissions],
        "update": [CanChangeOrDestroyEvent],
        "partial_update": [CanChangeOrDestroyEvent],
        "destroy": [CanChangeOrDestroyEvent],
    }
    per_action_serializer = {
        "list": ListEventSerializer,
        "retrieve": DetailEventSerializer,
        "create": CreateEventSerializer,
        "update": UpdateEventSerializer,
        "partial_update": UpdateEventSerializer,
    }

    def get_queryset(self):
        return Event.objects.get_only_active() if self.action == "list" else super().get_queryset()

    def get_serializer_class(self):
        serializer_class = self.per_action_serializer.get(self.action)

        return serializer_class if serializer_class else super().get_serializer_class()

    def get_permissions(self):
        permission_classes = self.per_action_permission.get(self.action)

        return [permission() for permission in permission_classes] if permission_classes else super().get_permissions()
