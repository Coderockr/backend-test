from django.db import models
from django.core.exceptions import ValidationError
import uuid

def validate_amount(value):
    if value < 0 :
        raise ValidationError(
            ('%(value)s is a invalid number, please only positive numbers!'),
            params={"value": value}
        )

class Investment(models.Model):
    id = models.UUIDField(default=uuid.uuid4, primary_key=True, editable=False)
    amount = models.PositiveIntegerField(validators=[validate_amount])
    created_at = models.DateField()

    owner = models.ForeignKey(
        "users.User",
        on_delete=models.CASCADE,
        related_name="investments"
    )
