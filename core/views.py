from rest_framework.viewsets import GenericViewSet
from rest_framework.mixins import (
    CreateModelMixin,
    UpdateModelMixin,
    DestroyModelMixin,
    RetrieveModelMixin,
)
from rest_framework.decorators import action
from rest_framework.response import Response

from .permissions import IsOwnProfile
from .serializers import UserSerializer
from .models import User

# Create your views here.
class UserViewSet(
    CreateModelMixin,
    UpdateModelMixin,
    DestroyModelMixin,
    GenericViewSet,
):
    queryset = User.objects.all()
    serializer_class = UserSerializer
    permission_classes = (IsOwnProfile,)

    @action(methods=["get"], detail=False)
    def whoami(self, request):
        if not request.user.id:
            return Response()
        serializer = self.get_serializer(data=request.user)
        serializer.is_valid(raise_exception=True)
        return Response(serializer.data)

    def get_queryset(self):
        qs = super().get_queryset()
        return qs.filter(pk=self.request.user.id)
