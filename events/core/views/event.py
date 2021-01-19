from django.shortcuts import get_object_or_404
from drf_spectacular.utils import extend_schema_view
from rest_framework import status
from rest_framework.decorators import action
from rest_framework.permissions import DjangoModelPermissions
from rest_framework.response import Response
from rest_framework.viewsets import ModelViewSet

from events.core.filters import EventFilter
from events.core.models import CustomUser, Event
from events.core.permissions import CanChangeOrDeleteEvent, IsEventOwner, ReadOnly
from events.core.schemas.event import (
    ADD_PARTICIPANT_SCHEMA,
    CREATE_SCHEMA,
    DESTROY_SCHEMA,
    LIST_SCHEMA,
    PARTIAL_UPDATE_SCHEMA,
    REMOVE_PARTICIPANT_SCHEMA,
    RETRIEVE_SCHEMA,
    UPDATE_SCHEMA,
)
from events.core.serializers import (
    CreateEventSerializer,
    DetailEventSerializer,
    ListEventSerializer,
    UpdateEventSerializer,
)


@extend_schema_view(
    list=LIST_SCHEMA,
    retrieve=RETRIEVE_SCHEMA,
    update=UPDATE_SCHEMA,
    partial_update=PARTIAL_UPDATE_SCHEMA,
    create=CREATE_SCHEMA,
    destroy=DESTROY_SCHEMA,
    add_participant=ADD_PARTICIPANT_SCHEMA,
    remove_participant=REMOVE_PARTICIPANT_SCHEMA,
)
class EventViewSet(ModelViewSet):
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
            return Response({}, status=status.HTTP_200_OK)

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

        return Response({"detail": "User not participate this event."}, status=status.HTTP_400_BAD_REQUEST)

    def get_queryset(self):
        return Event.objects.get_only_active() if self.action == "list" else super().get_queryset()

    def get_serializer_class(self):
        serializer_class = self.per_action_serializer.get(self.action)

        return serializer_class if serializer_class else super().get_serializer_class()

    def get_permissions(self):
        permission_classes = self.per_action_permission.get(self.action)

        return [permission() for permission in permission_classes] if permission_classes else super().get_permissions()
