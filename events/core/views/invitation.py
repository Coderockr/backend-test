from rest_framework import status
from rest_framework.decorators import action
from rest_framework.mixins import UpdateModelMixin
from rest_framework.permissions import IsAuthenticated
from rest_framework.response import Response
from rest_framework.viewsets import GenericViewSet

from events.core.exceptions import InvalidQueryParam
from events.core.models import Invitation
from events.core.models.user import CustomUser
from events.core.serializers import UpdateInvitationSerializer
from events.core.signals.email import send_email_to_register


class InvitationViewSet(GenericViewSet, UpdateModelMixin):
    queryset = Invitation.objects.all()
    serializer_class = UpdateInvitationSerializer

    @action(detail=False, permission_classes=[IsAuthenticated])
    def send_invitation(self, request):
        """
        Send invitation by passing invitation "type" and user "email" to send
        ...
        """
        type = request.query_params.get("type")
        to = request.query_params.get("to")

        self.__raise_if_invalid_invitation(request, type, to)

        was_sent = send_email_to_register(to)

        # invitations to register should not be created
        if was_sent:
            return Response({"detail": f"Was sent an register invitation email to {to}"}, status=status.HTTP_200_OK)
        else:
            invitation = Invitation()
            invitation.type = type
            invitation.invitation_from = request.user
            invitation.invitation_to = CustomUser.objects.get(email=to)
            invitation.save()

        return Response({"detail": "Invitation sent successfully"}, status=status.HTTP_200_OK)

    def __raise_if_invalid_invitation(self, request, type, to):
        # invite yourself or passed a invalid parameter
        if to == request.user.email or type is None or to is None:
            raise InvalidQueryParam()

        # invalid type
        try:
            Invitation().get_invitation_type(type)
        except AttributeError:
            raise InvalidQueryParam(detail="Invalid invitation type.")

        # sent same invite more than once
        invitations = Invitation.objects.filter(type=type, invitation_from=request.user, invitation_to__email=to)

        if invitations.exists():
            raise InvalidQueryParam(detail="This invitation was already sent before.")
