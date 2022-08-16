from rest_framework.viewsets import GenericViewSet
from rest_framework.mixins import (ListModelMixin, RetrieveModelMixin, 
                                   CreateModelMixin)
from rest_framework.response import Response
from rest_framework import status
from rest_framework.permissions import IsAuthenticated
from rest_framework.decorators import action

from .models import Investment
from .serializers import InvestmentSerializer, WithdrawalSerializer
from .permissions import IsOwnInvestment


# Create your views here.
class InvestmentViewSet(ListModelMixin, RetrieveModelMixin,
                        CreateModelMixin, GenericViewSet):
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
    return qs.filter(owner=self.request.user)


  @action(
    methods=['post'],
    detail=True,
    serializer_class=WithdrawalSerializer
  )
  def withdrawn(self, request, pk):
    investment = self.get_queryset().get(pk=pk)

    if not investment.active:
      return Response(status=status.HTTP_400_BAD_REQUEST)

    serializer = WithdrawalSerializer(
      instance=investment,
      data=request.data,
      context={'request': request}
    )
    serializer.is_valid(raise_exception=True)
    investment = serializer.save()
    
    return Response(
      WithdrawalSerializer().to_representation(investment),
      status=status.HTTP_202_ACCEPTED
    )
