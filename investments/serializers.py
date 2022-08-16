from dataclasses import fields
from rest_framework import serializers
from .validators import FutureDateValidator, NotFutureDateValidator
from .models import Investment


class InvestmentSerializer(serializers.ModelSerializer):
  balance = serializers.FloatField(read_only=True)
  class Meta:
    model = Investment
    fields = ('id', 'owner', 'amount', 'balance', 'active', 'created_at', 'withdrawn_at')
    extra_kwargs = {
      'active': {
        'read_only': True
      },
      'created_at': {
        'validators': [
          NotFutureDateValidator()
        ]
      },
      'owner': {
        'read_only': True
      },
      'withdrawn_at': {
        'read_only': True
      }
    }

  def create(self, validated_data):
    investment = Investment.objects.create(
      amount=validated_data['amount'],
      created_at=validated_data['created_at'],
      owner=self.context.get("request").user
    )
    return investment


class WithdrawalSerializer(serializers.ModelSerializer):
  class Meta:
    model = Investment
    fields = ('withdrawn_at',)
    extra_kwargs = {
      'withdrawn_at': {
        'validators': [
          FutureDateValidator()
        ]
      },
    }


  def save(self, **kwargs):
    self.instance.active = False
    return super().save(**kwargs)
  

  def to_representation(self, instance):
    return InvestmentSerializer().to_representation(instance)
