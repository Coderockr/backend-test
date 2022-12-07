from django.forms import model_to_dict
from django.shortcuts import get_object_or_404
from rest_framework import generics
from rest_framework.views import Response, status
from datetime import date
from decimal import Decimal
from django.core.paginator import Paginator

import ipdb

from investments.models import Investment
from investments.serializers import InvestmentDetailSerializer, InvestmentSerializer, InvestmentWithdrawnDetailSerializer


def validate_dates(investment, today_date, investment_date):

    today_separeted_date = str(today_date).split("/")

    separeted_date = str(investment_date).split("/")
    
    if int(today_separeted_date[2]) == int(separeted_date[2]):
        if int(today_separeted_date[1]) > int(separeted_date[1]):

            months = int(today_separeted_date[1]) - int(separeted_date[1])
            investment.initial_amount = investment.amount

            for _ in range(months):
                new_gains = Decimal(investment.amount) * Decimal(0.52/100)
                expected_balance =  (new_gains + Decimal(investment.amount))
                
                investment.gains = round(new_gains,2)
                investment.amount = expected_balance
                investment.expected_balance = round(expected_balance + new_gains , 2)

                investment.save()

            return investment 

    if int(today_separeted_date[2]) - int(separeted_date[2]) == 1 :

        if int(today_separeted_date[1]) == int(separeted_date[1]):

            if int(today_separeted_date[0]) == int(separeted_date[0]):

                new_gains = 12 * (Decimal(investment.amount) * Decimal((0.52/100)))

                expected_balance =  (new_gains + Decimal(investment.amount))
                
                investment.gains = round(new_gains,2)
                investment.initial_amount = investment.amount
                investment.amount = expected_balance
                investment.expected_balance = round(expected_balance + new_gains , 2)

                investment.save()

                return investment

    if (
        int(today_separeted_date[2]) - int(separeted_date[2]) == 1
        or
        int(today_separeted_date[2]) - int(separeted_date[2]) == 2):

        years = int(today_separeted_date[2]) - int(separeted_date[2])
        
        if int(today_separeted_date[1]) == int(separeted_date[1]):
            if int(today_separeted_date[0]) == int(separeted_date[0]):
                new_gains = (years * 12) * (Decimal(investment.amount) * Decimal((0.52/100)))

                expected_balance =  (new_gains + Decimal(investment.amount))
                
                investment.gains = round(new_gains,2)
                investment.initial_amount = investment.amount
                investment.amount = expected_balance
                investment.expected_balance = round(expected_balance + new_gains , 2)

                investment.save()
                
                return investment
                

        if int(today_separeted_date[1]) > int(separeted_date[1]):

            months = int(today_separeted_date[1]) - int(separeted_date[1])

            if int(today_separeted_date[0]) < int(separeted_date[0]):

                months = int(separeted_date[1]) -  int(today_separeted_date[1])
                investment.initial_amount = investment.amount

                for _ in range(months - 1):

                    new_gains = (Decimal(investment.amount) * Decimal((0.52/100)))

                    expected_balance =  (new_gains + Decimal(investment.amount))
                    
                    investment.gains = round(new_gains,2)
                    investment.amount = expected_balance
                    investment.expected_balance = round(expected_balance + new_gains , 2)
                    investment.save()
                    
                    return investment

            years = int(today_separeted_date[2]) - int(separeted_date[2])
            
            for _ in range(months):

                new_gains = (years * 12) * (Decimal(investment.amount) * Decimal((0.52/100)))

                expected_balance =  (new_gains + Decimal(investment.amount))
                
                investment.gains = round(new_gains,2)
                investment.amount = expected_balance
                investment.expected_balance = round(expected_balance + new_gains , 2)

                investment.save()
                
                return investment

    if int(today_separeted_date[2]) - int(separeted_date[2]) > 2:

        if int(today_separeted_date[1]) == int(separeted_date[1]):
            
            years = int(today_separeted_date[2]) - int(separeted_date[2])

            new_gains = (years * 12) * (Decimal(investment.amount) * Decimal((0.52/100)))

            expected_balance =  (new_gains + Decimal(investment.amount))
            
            investment.gains = round(new_gains,2)
            investment.amount = expected_balance
            investment.expected_balance = round(expected_balance + new_gains , 2)

            investment.save()
            
            return investment

        if int(today_separeted_date[1]) > int(separeted_date[1]):
            months = int(today_separeted_date[1]) - int(separeted_date[1])
            years = int(today_separeted_date[2]) - int(separeted_date[2])

            if int(today_separeted_date[0]) < int(separeted_date[0]):
                investment.initial_amount = investment.amount
                
                for _ in range(months - 1):

                    new_gains = (years * 12) * (Decimal(investment.amount) * Decimal((0.52/100)))

                    expected_balance =  (new_gains + Decimal(investment.amount))
                    
                    investment.gains = round(new_gains,2)
                    investment.amount = expected_balance
                    investment.expected_balance = round(expected_balance + new_gains , 2)

                    investment.save()
                    
                    return investment
            
            for _ in range(months):

                new_gains = (years * 12) * (Decimal(investment.amount) * Decimal((0.52/100)))

                expected_balance =  (new_gains + Decimal(investment.amount))
                
                investment.gains = round(new_gains,2)
                investment.amount = expected_balance
                investment.expected_balance = round(expected_balance + new_gains , 2)

                investment.save()
                
                return investment

class ListInvestmentView(generics.ListAPIView):
    queryset = Investment.objects.all()
    serializer_class = InvestmentSerializer

class ListOneUserInvestmentsView(generics.ListAPIView):
    queryset = Investment.objects.all()
    serializer_class = InvestmentSerializer

    lookup_url_kwarg = 'owner_id'

    def get_queryset(self):
        return self.queryset.filter(owner_id=self.kwargs['owner_id'])

class CreateInvestmentView(generics.CreateAPIView):

    queryset = Investment.objects.all()
    serializer_class = InvestmentDetailSerializer

    lookup_url_kwarg = 'owner_id'

class RetrieveUpdateDestroyInvestmentDetailView(
    generics.RetrieveDestroyAPIView
    ):

    queryset = Investment.objects.all()
    serializer_class = InvestmentDetailSerializer

    lookup_url_kwarg = 'investment_id'

    def retrieve(self, request, *args, **kwargs):
        investment_id = self.kwargs['investment_id']

        investment = get_object_or_404(Investment, pk=investment_id)

        today_date = date.today()

        today_date_formated = today_date.strftime("%d/%m/%Y")

        formated_date = investment.created_at.strftime("%d/%m/%Y")

        result = validate_dates(investment, today_date_formated, formated_date)

        return super().retrieve(result)


    def destroy(self, request, *args, **kwargs):
        investment_id = self.kwargs['investment_id']

        investment = get_object_or_404(Investment, pk=investment_id)

        investment.isActive = False

        investment.save()

        return Response({}, status.HTTP_204_NO_CONTENT)

class WithdrawnInvestmentView(generics.CreateAPIView):
    queryset = Investment.objects.all()
    serializer_class = InvestmentWithdrawnDetailSerializer

    lookup_url_kwarg = 'investment_id'


    