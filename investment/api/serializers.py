from rest_framework import serializers
from decimal import Decimal
from datetime import date, timedelta
from investment.models import Investments, Owner

class OwnerSerializer(serializers.ModelSerializer):
    class Meta:
        model = Owner
        fields = "__all__"
        depth = 1

class InvestmentSerializer(serializers.ModelSerializer):

    class Meta:
        model = Investments
        fields = "__all__"

    # @staticmethod
    # def calc_investment_date(update_date):
    #     today = date.today()
    #     days = (today - update_date).days
    #     months_in_days = days - (days % 30)
    #     new_update_date = update_date + timedelta(days=months_in_days)
    #     months = days // 30
    #     return months, new_update_date

    # def create(self, validated_data): 
    #     calc = self.calc_investment_date(validated_data.get('update_date'))
    #     gain = validated_data.get('amount') * (1 + Decimal(0.0052))**calc[0]
    #     # id = self.id
    #     validated_data.update({'amount':gain})
    #     validated_data.update({'update_date':calc[1]})
    #     # value = gain*(1+Decimal(0.0052))**1
    #     investment = InvestmentSerializer(**validated_data)
    #     return investment


class InvestmentNestedSerializer(serializers.ModelSerializer):
    owner = OwnerSerializer()

    class Meta:
        model = Investments
        fields = ('id', 'owner', 'creation_date', 'balance', 'expected_balance')

class InvestmentGetNestedSerializer(serializers.ModelSerializer):
    owner = OwnerSerializer()

    class Meta:
        model = Investments
        fields = ('id', 'owner', 'creation_date', 'amount', 'balance', 'expected_balance', 'income', 'withdrawal_date')
