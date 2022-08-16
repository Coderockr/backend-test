from rest_framework.serializers import ModelSerializer
from rest_framework.fields import CurrentUserDefault
from .validators import NotFutureDateValidator
from .models import Investment


class InvestmentSerializer(ModelSerializer):
  class Meta:
    model = Investment
    fields = ('id', 'owner', 'amount', 'active', 'created_at')
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
      }
    }

  def create(self, validated_data):
    investment = Investment.objects.create(
      amount=validated_data['amount'],
      created_at=validated_data['created_at'],
      owner=self.context.get("request").user
    )
    return investment

  def update(self, instance, validated_data):
    ...