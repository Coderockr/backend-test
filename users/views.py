from rest_framework import generics

from users.models import User
from users.serializers import UserDetailSerializer, UserSerializer

class CreateUserView(generics.ListCreateAPIView):
    queryset= User.objects.all()
    serializer_class= UserSerializer

class ListUpdateDeleteDetailUserView(generics.RetrieveUpdateDestroyAPIView):

    queryset = User.objects.all()
    serializer_class = UserDetailSerializer

    lookup_field = "id"
