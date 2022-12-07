from django.shortcuts import get_object_or_404
from rest_framework import generics
from rest_framework.views import Response, status
import ipdb

from users.models import User
from users.serializers import UserDetailSerializer, UserSerializer

class CreateUserView(generics.ListCreateAPIView):
    queryset= User.objects.all()
    serializer_class= UserSerializer

class ListUpdateDeleteDetailUserView(generics.RetrieveUpdateDestroyAPIView):

    queryset = User.objects.all()
    serializer_class = UserDetailSerializer

    lookup_field = "id"

    def destroy(self, request, *args, **kwargs):
        user_id = self.kwargs['id']

        user = get_object_or_404(User, pk=user_id)

        user.isActive = False

        user.save()

        return Response({}, status.HTTP_204_NO_CONTENT)