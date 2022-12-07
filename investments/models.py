from django.db import models
from django.core.exceptions import ValidationError
from datetime import date
import ipdb
import uuid
from rest_framework.exceptions import APIException
from rest_framework.views import status

class CustomValidation(APIException):
    status_code = status.HTTP_500_INTERNAL_SERVER_ERROR
    default_detail = "A server error occured."

    def __init__(self, detail, status_code):
        if status_code is not None:self.status_code=status_code
        if detail is not None:
            self.detail={"detail": detail}

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

    if today_formated.split('/')[2] < formated_date.split('/')[2]:
        raise CustomValidation("Invalid Date!", 400)

    if today_formated.split('/')[2] == formated_date.split('/')[2]:
        if today_formated.split('/')[1] < formated_date.split('/')[1]:
            raise CustomValidation("Invalid Date!", 400)


    if today_formated.split('/')[2] == formated_date.split('/')[2]:
        if today_formated.split('/')[1] == formated_date.split('/')[1]:
            if today_formated.split('/')[0] < formated_date.split('/')[0]:
                raise CustomValidation("Invalid Date!", 400)

def validate_withdrawn_date(date_req):
    today = date.today()

    if int(str(today).split("-")[2]) > 31:
        ipdb.set_trace()
        raise CustomValidation("Invalid Date!",400)

    if int(str(today).split("-")[1]) > 12:
        ipdb.set_trace()

        raise CustomValidation("Invalid Date!",400)

    today_formated = str(today)

    formated_date = str(date_req)

    if today_formated.split('-')[0] < formated_date.split('-')[0]:
        raise CustomValidation("Invalid Date!",400)

    if today_formated.split('-')[0] == formated_date.split('-')[0]:
        if today_formated.split('-')[1] < formated_date.split('-')[1]:
            raise CustomValidation("Invalid Date!",400)


    if today_formated.split('-')[0] == formated_date.split('-')[0]:
        if today_formated.split('-')[1] == formated_date.split('-')[1]:
            if today_formated.split('-')[2] < formated_date.split('-')[2]:
                raise CustomValidation("Invalid Date!",400)


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

