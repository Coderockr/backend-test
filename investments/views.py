from django.shortcuts import get_object_or_404
from rest_framework import generics
import ipdb

from investments.models import Investment
from investments.serializers import InvestmentDetailSerializer, InvestmentSerializer
from users.models import User

class ListInvestmentView(generics.ListAPIView):
    queryset = Investment.objects.all()
    serializer_class = InvestmentSerializer


class CreateInvestmentView(generics.CreateAPIView):

    queryset = Investment.objects.all()
    serializer_class = InvestmentDetailSerializer

    lookup_url_kwarg = 'owner_id'

    def perform_create(self, serializer):
        owner_id = self.kwargs['owner_id']

        owner = get_object_or_404(User, pk=owner_id)
        
        serializer.save(owner=owner)

class RetrieveUpdateDestroyInvestmentDetailView(
    generics.RetrieveUpdateDestroyAPIView
    ):

    queryset = Investment.objects.all()
    serializer_class = InvestmentDetailSerializer

    lookup_url_kwarg = 'investment_id'