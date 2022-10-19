from datetime import datetime

import numpy as np
import pandas as pd
from rest_framework import serializers

from investments.models import Investment

class InvestmentSerializer(serializers.ModelSerializer):
    expected_balance = serializers.SerializerMethodField()
    withdrawn_balance = serializers.SerializerMethodField()
    
    class Meta:
        model = Investment
        fields = ('id', 'owner', 'creation_date', 'initial_amount', 'expected_balance', 'withdrawn_balance', 'withdrawn_date', 'active')
        extra_kwargs = {
            'active': {'read_only': True},
            'expected_balance': {'read_only': True},
            'withdrawn_date': {'read_only': True},
            'owner': {'required': True},
        }
    
    def calculate_number_of_months(self, investment):
        date_now = datetime.today()
        creation_date = datetime.strptime(str(investment.creation_date), '%Y-%m-%d')
        withdrawn_date = investment.withdrawn_date
        if withdrawn_date is None:    
            data = {'date1': [date_now], 'date2': [creation_date]}
        else:
            withdrawn_date = datetime.strptime(str(withdrawn_date), '%Y-%m-%d')
            data = {'date1': [withdrawn_date], 'date2': [creation_date]}
        df = pd.DataFrame(data=data)
        df['nb_months'] = ((df.date1 - df.date2)/np.timedelta64(1, 'M'))
        df['nb_months'] = df['nb_months'].astype(int)
        
        return df['nb_months'].item()
        
    def get_expected_balance(self, investment):
        nb_months = self.calculate_number_of_months(investment)
        initial_amount = investment.initial_amount
        expected_balance = round(float(initial_amount * (pow((1 + 0.52 / 100), nb_months))), 2)
    
        return expected_balance

    def get_withdrawn_balance(self, investment):
        expected_balance = self.get_expected_balance(investment)
        nb_months = self.calculate_number_of_months(investment)
        initial_amount = investment.initial_amount
        profit = expected_balance - initial_amount        
        if nb_months < 12:
            return round(float((profit * 0.775) + initial_amount), 2)
        elif nb_months >= 12 and nb_months < 24:
            return round(float((profit * 0.815) + initial_amount), 2)
        else:
            return round(float((profit * 0.85) + initial_amount), 2)


class WithdrawnSerializer(InvestmentSerializer):
    class Meta:
        model = Investment
        fields = ('id', 'owner', 'creation_date', 'initial_amount', 'expected_balance', 'withdrawn_balance', 'withdrawn_date', 'active')
        extra_kwargs = {
            'active': {'read_only': True},
            'expected_balance': {'read_only': True},
            'initial_amount': {'read_only': True},
            'creation_date': {'read_only': True},
            'owner': {'read_only': True},
            'withdrawn_date': {'required': True},
        }
