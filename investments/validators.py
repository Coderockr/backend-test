from django.utils.timezone import now
from rest_framework.validators import ValidationError


class NotFutureDateValidator:
    def __call__(self, value):
        if value > now():
            raise ValidationError("This field must not contain a future date.")


class FutureDateValidator:
    def __call__(self, value):
        if value < now():
            raise ValidationError("This field must contain a future date.")
