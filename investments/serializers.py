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
            "withdrawn_date"
        ]

        read_only_fields=[
            "id",
            "owner_id",
            "gains",
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
            "withdrawn_date"
        ]

        read_only_fields=[
            "id",
            "owner",
            "gains",
            "withdrawn_date"
        ]