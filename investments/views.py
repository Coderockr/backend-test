from rest_framework.viewsets import GenericViewSet
from rest_framework.mixins import (ListModelMixin, RetrieveModelMixin, 
                                   DestroyModelMixin, CreateModelMixin)
from rest_framework.response import Response
from rest_framework import status
from rest_framework.permissions import IsAuthenticated

from .models import Investment
from .serializers import InvestmentSerializer
from .permissions import IsOwnInvestment
from .services import interest_svc


# Create your views here.
class InvestmentViewSet(ListModelMixin, RetrieveModelMixin,
                        DestroyModelMixin, CreateModelMixin,
                        GenericViewSet):
  queryset = Investment.objects.all()
  serializer_class = InvestmentSerializer
  permission_classes = (IsAuthenticated, IsOwnInvestment)


  def create(self, request):
    serializer = self.get_serializer(data=request.data, context={'request': request})
    serializer.is_valid(raise_exception=True)
    self.perform_create(serializer)
    headers = self.get_success_headers(serializer.data)
    return Response(serializer.data, status=status.HTTP_201_CREATED, headers=headers)


  def get_queryset(self):
    qs = super().get_queryset()
    
    qs = interest_svc.calculate_gain(qs)

    return qs.filter(owner=self.request.user)
