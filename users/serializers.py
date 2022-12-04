from rest_framework import serializers
from rest_framework.exceptions import APIException

from investments.serializers import InvestmentSerializer

from users.models import User

class Error(APIException):
    status_code = 403

class UserSerializer(serializers.ModelSerializer):
    class Meta:
        model = User

        fields =[
            "id",
            "email",
            "username"
        ]

        read_only_fields=["id"]

    def create(self, validated_data):
        if User.objects.filter(email=validated_data["email"]).exists():
            raise Error({"message": "Email already been used!"})
        return User.objects.create_user(**validated_data)

class UserDetailSerializer(serializers.ModelSerializer):
    investments = InvestmentSerializer(many=True)
    class Meta:
        model = User

        fields = [ "username","email", "investments"]