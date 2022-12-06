from django.shortcuts import get_object_or_404
from rest_framework import serializers

from investments.models import Investment
from users.models import User
import ipdb

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
            "isActive",
            "initial_amount",
            "withdrawn_date"
        ]

        read_only_fields=[
            "id",
            "owner",
            "gains",
            "isActive",
            "expected_balance",
            "initial_amount",
            "withdrawn_date"
        ]

    def create(self, validated_data: dict) -> Investment:
        validated_data['initial_amount'] = validated_data['amount']
        return Investment.objects.create(**validated_data)
