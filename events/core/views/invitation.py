import ast

from rest_framework import status
from rest_framework.decorators import action
from rest_framework.mixins import UpdateModelMixin
from rest_framework.permissions import IsAuthenticated
from rest_framework.response import Response
from rest_framework.viewsets import GenericViewSet

from events.core.exceptions import InvalidQueryParam
from events.core.models import CustomUser, Invitation
from events.core.serializers import UpdateInvitationSerializer
from events.core.signals.email import is_unregistered_destination, send_email_to_register


class InvitationViewSet(GenericViewSet, UpdateModelMixin):
    queryset = Invitation.objects.all()
    serializer_class = UpdateInvitationSerializer

    @action(detail=False, permission_classes=[IsAuthenticated])
    def send_invitation(self, request):
        """
        Send invitation by passing two query params:
            - type -> some choice of Invitation.INVITATION_TYPE_CHOICES
            - to -> must be an email list or "all_friends" to send to all_friends
        ...
        """
        type = request.query_params.get("type")
        emails = request.query_params.get("to")

        emails = self.__raise_if_invalid_invitation(request, type, emails)
        type = type.upper()

        if emails == "all_friends":
            self.__send_invitation_to_all_friends(request.user, type)
        else:
            self.__send_invitations(request.user, type, emails)

        return Response({"detail": "Invitation sent successfully"}, status=status.HTTP_200_OK)

    def __send_invitations(self, invitation_from, type, emails):
        for email in emails:
            if is_unregistered_destination(email):
                send_email_to_register(email)
            else:
                user = CustomUser.objects.get(email=email)
                Invitation.objects.create(type=type, invitation_from=invitation_from, invitation_to=user)

    def __send_invitation_to_all_friends(self, user, type):
        friends = CustomUser.objects.get_all_friends(user)

        for friend in friends:
            Invitation.objects.create(type=type, invitation_from=user, invitation_to=friend)

    def __raise_if_invalid_invitation(self, request, invitation_type, emails):
        """
        if not an invalid invitation, return the email list

        Args:
            request: request
            invitation_type (string): some choice of Invitation.INVITATION_TYPE_CHOICES
            emails (string): must be an email list string "['foo@foo.com', 'bar@bar.com']" \
                or "all_friends" to send to all_friends

        Raises:
            InvalidQueryParam: 1 -> if error when converting string to list
            InvalidQueryParam: 2 -> if invalid query params
            InvalidQueryParam: 3 -> if invited yourself
            InvalidQueryParam: 4 -> if invalid invitation type
            InvalidQueryParam: 5 -> if same invite more than once
        """
        # "all" is a valid query params, must be ignored
        if emails == "all_friends":
            return emails

        # convert string "['foo', 'bar']" to list ['foo', 'bar']
        try:
            emails = ast.literal_eval(emails)
        except SyntaxError:  # pragma: no cover -> out of scope
            raise InvalidQueryParam()  # 1

        # invalid query params
        invalid_invitation_type = invitation_type is None
        invalid_emails = emails is None or type(emails) is not list
        if invalid_invitation_type or invalid_emails:
            raise InvalidQueryParam()  # 2

        # invited yourself
        if request.user.email in emails:
            raise InvalidQueryParam(detail="Can not send invitation to yourself.")  # 3

        # invalid invitation_type
        try:
            Invitation().get_invitation_type(invitation_type)
        except AttributeError:
            raise InvalidQueryParam(detail="Invalid invitation type.")  # 4

        for email in emails:
            # invite already existing
            if Invitation.objects.is_duplicated(invitation_type, request.user, email):
                raise InvalidQueryParam(detail=f"This invitation to {email} was already sent before.")  # 5

        return emails
