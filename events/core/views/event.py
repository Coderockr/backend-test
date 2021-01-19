from django.shortcuts import get_object_or_404
from rest_framework import status
from rest_framework.decorators import action
from rest_framework.permissions import DjangoModelPermissions
from rest_framework.response import Response
from rest_framework.viewsets import ModelViewSet

from events.core.filters import EventFilter
from events.core.models import CustomUser, Event
from events.core.permissions import CanChangeOrDeleteEvent, IsEventOwner, ReadOnly
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
        "update": [CanChangeOrDeleteEvent],
        "partial_update": [CanChangeOrDeleteEvent],
        "destroy": [CanChangeOrDeleteEvent],
    }
    per_action_serializer = {
        "list": ListEventSerializer,
        "retrieve": DetailEventSerializer,
        "create": CreateEventSerializer,
        "update": UpdateEventSerializer,
        "partial_update": UpdateEventSerializer,
    }

    @action(
        detail=True,
        methods=["post"],
        permission_classes=[IsEventOwner],
        url_path="add_participant/(?P<user_id>\d+)",  # noqa
    )
    def add_participant(self, request, user_id, pk=None):
        event = self.get_object()
        user = get_object_or_404(CustomUser, pk=user_id)

        participant_added = Event.objects.add_participant(event, user)
        if participant_added:
            return Response({}, status=status.HTTP_204_NO_CONTENT)

        return Response({"detail": "Unable to add participant."}, status=status.HTTP_400_BAD_REQUEST)

    @action(
        detail=True,
        methods=["post"],
        permission_classes=[IsEventOwner],
        url_path="remove_participant/(?P<user_id>\d+)",  # noqa
    )
    def remove_participant(self, request, user_id, pk=None):
        event = self.get_object()
        user = get_object_or_404(CustomUser, pk=user_id)

        participant_removed = Event.objects.remove_participant(event, user)
        if participant_removed:
            return Response({}, status=status.HTTP_204_NO_CONTENT)

        return Response({"detail": "User not participate in this event."}, status=status.HTTP_400_BAD_REQUEST)

    def get_queryset(self):
        return Event.objects.get_only_active() if self.action == "list" else super().get_queryset()

    def get_serializer_class(self):
        serializer_class = self.per_action_serializer.get(self.action)

        return serializer_class if serializer_class else super().get_serializer_class()

    def get_permissions(self):
        permission_classes = self.per_action_permission.get(self.action)

        return [permission() for permission in permission_classes] if permission_classes else super().get_permissions()
