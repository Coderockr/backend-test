from http import HTTPStatus
from rest_framework.viewsets import ModelViewSet
from rest_framework.response import Response
from rest_framework.decorators import action

from .permissions import IsOwnProfile
from .serializers import UserSerializer
from .models import User

# Create your views here.
class UserViewSet(ModelViewSet):
  queryset = User.objects.all()
  serializer_class = UserSerializer
  permission_classes = (IsOwnProfile,)
