from djoser.views import UserViewSet as DjoserUserViewSet
from rest_framework import status
from rest_framework.decorators import action
from rest_framework.permissions import AllowAny, IsAuthenticated
from rest_framework.response import Response
from rest_framework.test import APIRequestFactory
from rest_framework.viewsets import GenericViewSet

from events.core.filters import MyEventsFilter
from events.core.models import Event
from events.core.models.user import CustomUser
from events.core.serializers import ListEventSerializer
from events.core.serializers.invitation import ListInvitationSerializer
from events.core.serializers.user import ListUserSerializer


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
            Response(
                {"detail": "Account successfully activated"},
                status=status.HTTP_204_NO_CONTENT,
            )
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
    def friends_requests(self, request):
        queryset = CustomUser.objects.get_all_friends_requests(request.user)
        paginated_queryset = self.paginate_queryset(queryset)
        serializer = ListInvitationSerializer(instance=paginated_queryset, many=True)

        return self.get_paginated_response(serializer.data)

    @action(detail=True, methods=["delete"], permission_classes=[IsAuthenticated])
    def remove_friend(self, request, pk=None):
        friend = self.get_object()

        CustomUser.objects.remove_friend(request.user, friend)

        return Response({"detail": "Friend remove succeed."}, status=status.HTTP_204_NO_CONTENT)
