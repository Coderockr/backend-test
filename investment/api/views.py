from rest_framework import viewsets, filters
from rest_framework.decorators import action
from rest_framework.response import Response
from datetime import date
from decimal import Decimal
from django.shortcuts import get_object_or_404

from django_filters.rest_framework import DjangoFilterBackend

from investment.api.serializers import OwnerSerializer, InvestmentSerializer, InvestmentNestedSerializer, \
InvestmentGetNestedSerializer

from investment.models import Investments, Owner

class OwnerViewSet(viewsets.ModelViewSet):
    queryset = Owner.objects.all()
    serializer_class = OwnerSerializer

class InvestmentsViewSet(viewsets.ModelViewSet):
    queryset = Investments.objects.all()
    serializer_class = InvestmentSerializer
    filter_backends = [DjangoFilterBackend, filters.SearchFilter]
    filterset_fields = ["owner__id"]
    search_fields = ["owner__name"]

    def create(self, request, *args, **kwargs):
        request.data.update({'update_date':request.data.get('creation_date')})
        request.data.update({'balance':request.data.get('amount')})
        return super().create(request, *args, **kwargs)


    def retrieve(self, request, *args, **kwargs):
        self.serializer_class = InvestmentGetNestedSerializer
        return super().retrieve(request, *args, **kwargs,)
    
    def list(self, request, *args, **kwargs):
        self.serializer_class = InvestmentNestedSerializer
        return super().list(request, *args, **kwargs)

    @action(detail=True, methods=['get'])
    def withdraw(self, request, pk=None):
        def investment_age(creation_date):
            today = date.today()
            diff_days = today - creation_date
            years = diff_days.days//365
            return years

        investment = get_object_or_404(Investments, pk=pk)
        income = investment.income
        balance = investment.balance
        years = investment_age(investment.creation_date)
        if years < 1:
            taxation = (income*Decimal(0.225))
        elif years >= 1 and years <= 2:
            taxation = (income*Decimal(0.185))
        elif years > 2:
            taxation = (income*Decimal(0.15))
        amount_withdrawn = balance-taxation
        if not investment.withdrawal_date:
            investment.withdrawal_date = date.today()
            investment.balance = investment.income
            investment.save()
            return Response({'valor do saque: ':amount_withdrawn})
        else:
            return Response({'valor do saque: ':amount_withdrawn+investment.amount})

                
    