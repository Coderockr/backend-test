from rest_framework import serializers
import ipdb

from investments.serializers import InvestmentSerializer

from users.models import User
from rest_framework.exceptions import APIException
from rest_framework.views import status

class CustomValidation(APIException):
    status_code = status.HTTP_500_INTERNAL_SERVER_ERROR
    default_detail = "A server error occured."

    def __init__(self, detail, status_code):
        if status_code is not None:self.status_code=status_code
        if detail is not None:
            self.detail={"detail": detail}

class UserSerializer(serializers.ModelSerializer):
    class Meta:
        model = User

        fields =[
            "id",
            "email",
            "username",
            "isActive"
        ]

        read_only_fields=["id", "isActive"]

    def create(self, validated_data):
        if User.objects.filter(email=validated_data["email"]).exists():
           raise CustomValidation("Email aready in use!", 409)
        return User.objects.create_user(**validated_data)


class UserDetailSerializer(serializers.ModelSerializer):
    investments = InvestmentSerializer(many=True)
    class Meta:
        model = User

        fields = [ "username", "email", "investments", "isActive"]