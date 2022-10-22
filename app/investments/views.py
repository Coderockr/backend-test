from datetime import datetime

from django.core.mail import send_mail
from rest_framework import mixins, status, viewsets
from rest_framework.decorators import action
from rest_framework.response import Response

from investments.models import Investment
from investments.serializers import InvestmentSerializer, WithdrawnSerializer


class InvestmentViewSet(
    mixins.CreateModelMixin,
    mixins.RetrieveModelMixin,
    mixins.ListModelMixin,
    viewsets.GenericViewSet):
    queryset = Investment.objects.all().order_by('-id')
    serializer_class = InvestmentSerializer

    def create(self, request):
        serializer = self.get_serializer(data=request.data)
        
        # valid creation date
        if request.data.get('creation_date') is None:
            return Response('The creation date is mandatory.', status=status.HTTP_400_BAD_REQUEST)
        creation_date = datetime.strptime(request.data.get('creation_date'), '%Y-%m-%d')
        now = datetime.today()
        diff = (now - creation_date).days
        if(diff < 0):
            return Response('The creation date of an investment can be today or a date in the past.', status=status.HTTP_400_BAD_REQUEST)
        
        #valid amout
        initial_amount = request.data.get('initial_amount')
        if(int(initial_amount) < 0):
            return Response('The initial amount needs to be positive.', status=status.HTTP_400_BAD_REQUEST)
        
        serializer.is_valid(raise_exception=True)
        self.perform_create(serializer)
        headers = self.get_success_headers(serializer.data)
        return Response(serializer.data, status=status.HTTP_201_CREATED, headers=headers)


class WithdrawnViewSet(mixins.UpdateModelMixin,viewsets.GenericViewSet):
    queryset = Investment.objects.all().order_by('-id')
    serializer_class = WithdrawnSerializer
    
    def send_email_on_withdrawn(self, investment,serializer):
        initial_amount = investment.initial_amount
        withdrawn_balance = serializer.get_withdrawn_balance(investment)
        name = investment.owner.name
        email = send_mail(
            'You have a new investment',
            f'Hi {name}, \n\nYour investment has been withdrawn. The initial investment amount was {initial_amount} and the amount withdrawn was {withdrawn_balance}. \nThank you for the trust, have a great day',
            'investmentscoderockr@outlook.com',
            [investment.owner.email],
        )

    @action(detail=True, methods=['put'])        
    def withdrawn(self, request, pk=None):
        investments = self.get_object()
        serializer = WithdrawnSerializer(investments, data=request.data)
        
        withdrawn_date = datetime.strptime(request.data['withdrawn_date'], '%Y-%m-%d')
        creation_date = datetime.strptime(str(investments.creation_date), '%Y-%m-%d')
        now = datetime.today()
        diff_now_withdrawn_date = (now - withdrawn_date).days
        diff_creation_date_withdrawn_date = (withdrawn_date - creation_date).days
        if(diff_now_withdrawn_date < 0 or diff_creation_date_withdrawn_date < 0):
            return Response('Withdrawals can happen in the past or today, but cannot happen before the investment creation or the future.', status=status.HTTP_400_BAD_REQUEST)
        elif investments.active == False:
            return Response('The withdrawal of this investment has already been made.', status=status.HTTP_400_BAD_REQUEST)
        serializer.is_valid(raise_exception=True)
        investments.active = False
        self.send_email_on_withdrawn(investments, serializer)
        serializer.save()
        return Response(serializer.data)
    

        