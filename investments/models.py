from django.db import models
from django.core.exceptions import ValidationError
from datetime import date
import ipdb
import uuid

from utilities.api_exceptions import CustomApiException

def validate_amount(value):
    if value < 0 :
        raise ValidationError(
            (f'{value} is a invalid number, please only positive numbers!')
        )

def validate_date(date_req):
    today = date.today()

    day = str(today).split("-")[2]
    month = str(today).split("-")[1]

    if int(day) > 31:
        raise ValidationError(
            (f'{day} is a invalid day, please insert a valid day!')
        )

    if int(month) > 12:
        raise ValidationError(
            (f'{month} is a invalid month, please insert a valid month!')
        )

    today_formated = today.strftime("%d/%m/%Y")

    formated_date = date_req.strftime("%d/%m/%Y")

    if today_formated < formated_date:
        raise ValidationError(
            (f'{formated_date} is a invalid date, please investment only can be created in the past or today!')
        )

def validate_withdrawn_date(date_req):
    today = date.today()

    if int(str(today).split("-")[2]) > 31:
        ipdb.set_trace()
        raise CustomApiException(400, "Invalid Date!")

    if int(str(today).split("-")[1]) > 12:
        ipdb.set_trace()

        raise CustomApiException(400, "Invalid Date!")

    today_formated = str(today)

    formated_date = str(date_req)

    if today_formated.split('-')[0] < formated_date.split('-')[0]:
        raise CustomApiException(400, "Invalid Date!")

    if today_formated.split('-')[0] == formated_date.split('-')[0]:
        if today_formated.split('-')[1] < formated_date.split('-')[1]:
            raise CustomApiException(400, "Invalid Date!")


    if today_formated.split('-')[0] == formated_date.split('-')[0]:
        if today_formated.split('-')[1] == formated_date.split('-')[1]:
            if today_formated.split('-')[2] < formated_date.split('-')[2]:
                raise CustomApiException(400, "Invalid Date!")


class Investment(models.Model):
    id = models.UUIDField(default=uuid.uuid4, primary_key=True, editable=False)
    initial_amount = models.DecimalField(decimal_places=2,max_digits=15, null=True)
    amount = models.DecimalField(decimal_places=2,max_digits=10,validators=[validate_amount])
    created_at = models.DateField(validators=[validate_date])
    gains = models.DecimalField(decimal_places=2,max_digits=5, null=True, default=0)
    withdrawn_date = models.DateField(null=True, validators=[validate_withdrawn_date])
    withdrew_amount = models.DecimalField(decimal_places=2,max_digits=15, null=True)
    expected_balance = models.DecimalField(decimal_places=2,max_digits=15, null=True, default=0)
    isActive = models.BooleanField(default=True)

    owner = models.ForeignKey(
        "users.User",
        on_delete=models.CASCADE,
        related_name="investments"
    )


#Caso tenha acontecido withdrawn colocar a data 
