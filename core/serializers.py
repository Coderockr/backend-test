from rest_framework import serializers
from rest_framework.validators import UniqueValidator
from .models import User


class UserSerializer(serializers.ModelSerializer):
  class Meta:
    model = User
    fields = ('id', 'email', 'password')
    extra_kwargs = {
      'email': {
        'validators': [
          UniqueValidator(
            queryset=User.objects.all()
          )
        ]
      },
      'password': {
        'write_only': True,
        'style': {
          'input_type': 'password'
        }
      }
    }


  def create(self, validated_data):
    user = User.objects.create_user(
      email=validated_data['email'],
      password=validated_data['password'],
      username=validated_data['email']
    )

    return user


  def update(self, instance, validated_data):
    if 'password' in validated_data:
      password = validated_data.pop('password')
      instance.set_password(password)
    return super().update(instance, validated_data)
