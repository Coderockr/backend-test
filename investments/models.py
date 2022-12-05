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
    amount = models.DecimalField(decimal_places=2,max_digits=10,validators=[validate_amount])
    created_at = models.DateField()
    gains = models.DecimalField(decimal_places=2,max_digits=5, null=True, default=0)
    withdrawn_date = models.DateField(null=True)

    owner = models.ForeignKey(
        "users.User",
        on_delete=models.CASCADE,
        related_name="investments"
    )

#Colocar gains, decimal e mudar amount
#Colocar withdrawnDate que começa vazio
#Caso tenha acontecido withdrawn colocar a data 
