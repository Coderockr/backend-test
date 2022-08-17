from dataclasses import fields
from rest_framework import serializers
from rest_framework.validators import ValidationError
from .validators import NotFutureDateValidator
from .models import Investment


class InvestmentSerializer(serializers.ModelSerializer):
    balance = serializers.DecimalField(max_digits=11, decimal_places=2, read_only=True)

    class Meta:
        model = Investment
        fields = "__all__"
        read_only_fields = ("active", "owner", "withdrawn_at")
        extra_kwargs = {"created_at": {"validators": [NotFutureDateValidator()]}}

    def create(self, validated_data):
        investment = Investment.objects.create(
            amount=validated_data["amount"],
            created_at=validated_data["created_at"],
            owner=self.context.get("request").user,
        )
        return investment


class WithdrawalSerializer(serializers.ModelSerializer):
    balance = serializers.DecimalField(max_digits=11, decimal_places=2, read_only=True)

    class Meta:
        model = Investment
        fields = "__all__"
        read_only_fields = (
            "id",
            "owner",
            "amount",
            "balance",
            "active",
            "created_at",
        )
        extra_kwargs = {
            "withdrawn_at": {"validators": [NotFutureDateValidator()]},
        }

    def is_valid(self, raise_exception=False):
        valid = super().is_valid(raise_exception)
        if raise_exception:
            if self.instance.created_at > self.validated_data["withdrawn_at"]:
                valid = False
                raise ValidationError(
                    {
                        "detail": "Withdrawn date must not be older than when investment was created."
                    }
                )
        return valid

    def save(self, **kwargs):
        self.instance.active = False
        return super().save(**kwargs)

    def to_representation(self, instance):
        return InvestmentSerializer().to_representation(instance)
