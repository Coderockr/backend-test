from drf_spectacular.utils import extend_schema_view
from rest_framework import status
from rest_framework.mixins import CreateModelMixin, DestroyModelMixin, UpdateModelMixin
from rest_framework.permissions import IsAuthenticated
from rest_framework.response import Response
from rest_framework.viewsets import GenericViewSet

from events.core.models import Invitation
from events.core.permissions import CanChangeInvitation, CanDeleteInvitation
from events.core.schemas.invitation import CREATE_SCHEMA, DESTROY_SCHEMA, PARTIAL_UPDATE_SCHEMA, UPDATE_SCHEMA
from events.core.serializers import CreateInvitationSerializer, UpdateInvitationSerializer


@extend_schema_view(
    create=CREATE_SCHEMA,
    update=UPDATE_SCHEMA,
    partial_update=PARTIAL_UPDATE_SCHEMA,
    destroy=DESTROY_SCHEMA,
)
class InvitationViewSet(GenericViewSet, CreateModelMixin, UpdateModelMixin, DestroyModelMixin):
    queryset = Invitation.objects.all()

    per_action_permission = {
        "create": [IsAuthenticated],
        "update": [CanChangeInvitation],
        "partial_update": [CanChangeInvitation],
        "destroy": [CanDeleteInvitation],
    }
    per_action_serializer = {
        "create": CreateInvitationSerializer,
        "update": UpdateInvitationSerializer,
        "partial_update": UpdateInvitationSerializer,
    }

    def create(self, request, *args, **kwargs):
        serializer = self.get_serializer(data=request.data)
        serializer.is_valid(raise_exception=True)
        self.perform_create(serializer)

        data = serializer.data

        if data.get("invitation_to"):
            return Response(serializer.data, status=status.HTTP_201_CREATED)

        return Response({}, status=status.HTTP_400_BAD_REQUEST)

    def get_serializer_class(self):
        serializer_class = self.per_action_serializer.get(self.action)

        return serializer_class if serializer_class else super().get_serializer_class()

    def get_permissions(self):
        permission_classes = self.per_action_permission.get(self.action)

        return [permission() for permission in permission_classes] if permission_classes else super().get_permissions()
