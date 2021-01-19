from djoser.views import UserViewSet as DjoserUserViewSet
from drf_spectacular.utils import extend_schema_view
from rest_framework import status
from rest_framework.decorators import action
from rest_framework.permissions import AllowAny, IsAuthenticated
from rest_framework.response import Response
from rest_framework.test import APIRequestFactory
from rest_framework.viewsets import GenericViewSet

from events.core.filters import MyEventsFilter
from events.core.models import CustomUser, Event, Invitation
from events.core.schemas.user import (
    ACCOUNT_ACTIVATION_USER,
    DELETE_USER,
    EVENT_INVITATIONS_USER,
    FRIENDS_USER,
    FRIENDSHIP_INVITATIONS_USER,
    MY_EVENTS_USER,
    REJECTED_EVENTS_USER,
)
from events.core.serializers import ListEventSerializer, ListInvitationSerializer, ListUserSerializer


@extend_schema_view(
    remove_friend=DELETE_USER,
    account_activation=ACCOUNT_ACTIVATION_USER,
    event_invitations=EVENT_INVITATIONS_USER,
    friends=FRIENDS_USER,
    friendship_invitations=FRIENDSHIP_INVITATIONS_USER,
    my_events=MY_EVENTS_USER,
    rejected_events=REJECTED_EVENTS_USER,
)
class UserViewSet(GenericViewSet):
    queryset = CustomUser.objects.all()

    # Get events that user is participating or that is own events
    @action(detail=False, permission_classes=[IsAuthenticated])
    def my_events(self, request):
        self.filterset_class = MyEventsFilter

        queryset = Event.objects.get_own_or_participating_events(request.user)
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
            Response({}, status=status.HTTP_200_OK)
            if response.status_code == status.HTTP_204_NO_CONTENT
            else Response(response.data)
        )

    @action(detail=False, permission_classes=[IsAuthenticated])
    def friends(self, request):
        queryset = CustomUser.objects.get_all_friends(request.user)
        paginated_queryset = self.paginate_queryset(queryset)
        serializer = ListUserSerializer(instance=paginated_queryset, many=True)

        return self.get_paginated_response(serializer.data)

    @action(detail=False, permission_classes=[IsAuthenticated])
    def friendship_invitations(self, request):
        queryset = CustomUser.objects.get_all_invitations_of_type(request.user, Invitation.FRIENDSHIP)
        paginated_queryset = self.paginate_queryset(queryset)
        serializer = ListInvitationSerializer(instance=paginated_queryset, many=True)

        return self.get_paginated_response(serializer.data)

    @action(detail=False, permission_classes=[IsAuthenticated])
    def event_invitations(self, request):
        queryset = CustomUser.objects.get_all_invitations_of_type(request.user, Invitation.EVENT)
        paginated_queryset = self.paginate_queryset(queryset)
        serializer = ListInvitationSerializer(instance=paginated_queryset, many=True)

        return self.get_paginated_response(serializer.data)

    @action(detail=False, permission_classes=[IsAuthenticated])
    def rejected_events(self, request):
        queryset = CustomUser.objects.get_all_invitations_of_type_and_status(
            request.user, Invitation.EVENT, Invitation.REJECTED
        )
        paginated_queryset = self.paginate_queryset(queryset)
        serializer = ListInvitationSerializer(instance=paginated_queryset, many=True)

        return self.get_paginated_response(serializer.data)

    @action(detail=True, methods=["delete"], permission_classes=[IsAuthenticated])
    def remove_friend(self, request, pk=None):
        friend = self.get_object()

        removed = CustomUser.objects.remove_friend(request.user, friend)
        if removed:
            return Response({}, status=status.HTTP_200_OK)

        return Response({"detail": "User is not your friend."}, status=status.HTTP_400_BAD_REQUEST)
