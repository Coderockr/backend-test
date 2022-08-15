from rest_framework import serializers
from .models import User

class UserSerializer(serializers.ModelSerializer):
  class Meta:
    model = User
    fields = ('id', 'email', 'password')
    extra_kwargs = {
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
      username=validated_data['email'],
      password=validated_data['password']
    )

    return user

  def update(self, instance, validated_data):
    if 'password' in validated_data:
      password = validated_data.pop('password')
      instance.set_password(password)
    return super().update(instance, validated_data)
