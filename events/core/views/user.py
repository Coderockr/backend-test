from djoser.views import UserViewSet as DjoserUserViewSet
from rest_framework import status
from rest_framework.decorators import action
from rest_framework.permissions import AllowAny, IsAuthenticated
from rest_framework.response import Response
from rest_framework.test import APIRequestFactory
from rest_framework.viewsets import GenericViewSet

from events.core.exceptions import InvalidQueryParam
from events.core.filters import MyEventsFilter
from events.core.models import Event
from events.core.models.user import CustomUser, Invitation
from events.core.serializers import ListEventSerializer


class UserViewSet(GenericViewSet):
    """
    Get events that user is participating or that is own events
    """

    filterset_class = MyEventsFilter

    def get_queryset(self):
        queryset = Event.objects.get_own_or_participating_events(self.request.user)

        return queryset

    @action(detail=False, permission_classes=[IsAuthenticated])
    def my_events(self, _):
        queryset = self.get_queryset()
        filtered_queryset = self.filter_queryset(queryset)
        paginated_data = self.paginate_queryset(filtered_queryset)
        serializer = ListEventSerializer(instance=paginated_data, many=True)

        return self.get_paginated_response(serializer.data)

    @action(
        detail=False,
        permission_classes=[AllowAny],
        url_path="account_activation/(?P<uid>[\w-]+)/(?P<token>[\w-]+)",  # noqa
    )
    def account_activation(self, _, uid, token):
        data = {"uid": uid, "token": token}

        path = "/auth/users/activation/"

        factory = APIRequestFactory()
        request = factory.post(path, data, format="json")
        view = DjoserUserViewSet.as_view({"post": "activation"})
        response = view(request)

        return (
            Response(
                {"detail": "Account successfully activated"},
                status=status.HTTP_204_NO_CONTENT,
            )
            if response.status_code == status.HTTP_204_NO_CONTENT
            else Response(response.json())
        )

    @action(detail=False, permission_classes=[IsAuthenticated])
    def send_invitation(self, request):
        """
        Send invitation by passing invitation "type" and user "email" to send
        ...
        """
        type = request.query_params.get("type")
        to = request.query_params.get("to")

        self.__raise_if_invalid_invitation(request, type, to)

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

        # unregistered email
        try:
            CustomUser.objects.get(email=to)
        except CustomUser.DoesNotExist:
            raise InvalidQueryParam(detail="Does not has a user with this email.")

        # sent same invite more than once
        invitations = Invitation.objects.filter(type=type, invitation_from=request.user, invitation_to__email=to)

        if invitations.exists():
            raise InvalidQueryParam(detail="This invitation was already sent before.")
