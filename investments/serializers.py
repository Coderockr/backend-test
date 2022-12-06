from decimal import Decimal
from django.shortcuts import get_object_or_404
from rest_framework import serializers
from django.core.exceptions import ValidationError

from investments.models import Investment
from users.models import User
import ipdb

from utilities.api_exceptions import CustomApiException

def validate_dates_and_tax(investment, today_date, investment_date):

    today_separeted_date = str(today_date).split("/")

    separeted_date = str(investment_date).split("/")
    
    if int(today_separeted_date[2]) == int(separeted_date[2]):
        if int(today_separeted_date[1]) > int(separeted_date[1]):

            result =  investment.amount - investment.initial_amount 

            tax = result * Decimal(22.5/100)

            charged = investment.amount - Decimal(tax)

            return round(charged, 2) 

    if int(today_separeted_date[2]) - int(separeted_date[2]) == 1 :

        if int(today_separeted_date[1]) == int(separeted_date[1]):

            if int(today_separeted_date[0]) == int(separeted_date[0]):

                result =  investment.amount - investment.initial_amount 

                tax = result * Decimal(18.5/100)

                charged = investment.amount - Decimal(tax)

                return round(charged, 2)

    if (
        int(today_separeted_date[2]) - int(separeted_date[2]) == 1
        or
        int(today_separeted_date[2]) - int(separeted_date[2]) == 2):
        
        if int(today_separeted_date[1]) == int(separeted_date[1]):
            if int(today_separeted_date[0]) == int(separeted_date[0]):
                result =  investment.amount - investment.initial_amount 

                tax = result * Decimal(18.5/100)

                charged = investment.amount - Decimal(tax)

                return round(charged, 2)
                

        if int(today_separeted_date[1]) > int(separeted_date[1]):

            if int(today_separeted_date[0]) < int(separeted_date[0]):

                result =  investment.amount - investment.initial_amount 

                tax = result * Decimal(18.5/100)

                charged = investment.amount - Decimal(tax)

                return round(charged, 2)
            
            result =  investment.amount - investment.initial_amount 

            tax = result * Decimal(18.5/100)

            charged = investment.amount - Decimal(tax)

            return round(charged, 2)

    if int(today_separeted_date[2]) - int(separeted_date[2]) > 2:

        if int(today_separeted_date[1]) == int(separeted_date[1]):
            
            result =  investment.amount - investment.initial_amount 

            tax = result * Decimal(18.5/100)

            charged = investment.amount - Decimal(tax)

            return round(charged, 2)

        if int(today_separeted_date[1]) > int(separeted_date[1]):
            if int(today_separeted_date[0]) < int(separeted_date[0]):
                
                result =  investment.amount - investment.initial_amount 

                tax = result * Decimal(18.5/100)

                charged = investment.amount - Decimal(tax)

                return round(charged, 2)
            
            result =  investment.amount - investment.initial_amount 

            tax = result * Decimal(18.5/100)

            charged = investment.amount - Decimal(tax)

            return round(charged, 2)


class InvestmentSerializer(serializers.ModelSerializer):
    class Meta:
        model = Investment

        fields =[
            "id",
            "amount",
            "created_at",
            "gains",
            "owner_id",
            "isActive",
            "initial_amount",
            "withdrawn_date"
        ]

        read_only_fields=[
            "id",
            "owner_id",
            "gains",
            "isActive",
            "initial_amount",
            "withdrawn_date"
        ]

class InvestmentDetailSerializer(serializers.ModelSerializer):

    class Meta:
        model = Investment

        fields = [
            "id",
            "amount",
            "created_at",
            "gains",
            "owner",
            "expected_balance",
            "initial_amount",
        ]

        read_only_fields=[
            "id",
            "owner",
            "gains",
            "expected_balance",
            "initial_amount",
        ]

    def create(self, validated_data: dict) -> Investment:
        validated_data['initial_amount'] = validated_data['amount']
        return Investment.objects.create(**validated_data)

class InvestmentWithdrawnDetailSerializer(serializers.ModelSerializer):

    class Meta:
        model = Investment

        fields = [
            "id",
            "amount",
            "created_at",
            "gains",
            "owner",
            "expected_balance",
            "initial_amount",
            "withdrawn_date",
            "withdrew_amount"
        ]

        read_only_fields=[
            "id",
            "amount",
            "created_at",
            "gains",
            "owner",
            "expected_balance",
            "isActive",
            "initial_amount",
            "withdrew_amount"
        ]

    def create(self, validated_data: dict) -> Investment:
        investment_id = self.context['view'].kwargs['investment_id']

        investment = get_object_or_404(Investment, pk=investment_id)
        # ipdb.set_trace()
        if investment.withdrawn_date != None:
            raise CustomApiException(409, "Investment has already been withdrew!")

        date = validated_data["withdrawn_date"]

        today_date = date.today()

        today_date_formated = today_date.strftime("%d/%m/%Y")

        formated_date = investment.created_at.strftime("%d/%m/%Y")

        result = validate_dates_and_tax(investment, today_date_formated, formated_date)

        investment.withdrew_amount = result
        investment.withdrawn_date = date
        investment.amount = 0
        investment.expected_balance = 0
        investment.gains = 0

        investment.save()

        return investment