from django.db import models
from django.conf import settings
from django.utils.timezone import now
from .services import interest_svc
from .utils import diff_month


# Create your models here.
class Investment(models.Model):
    owner = models.ForeignKey(settings.AUTH_USER_MODEL, on_delete=models.CASCADE)
    amount = models.FloatField()
    active = models.BooleanField(default=True, null=False)
    created_at = models.DateTimeField()
    withdrawn_at = models.DateTimeField(null=True)

    @property
    def balance(self):
        start_date = self.withdrawn_at if self.withdrawn_at else now()
        age = diff_month(start_date, self.created_at)

        if age == 0:
            return self.amount if self.active else 0

        gains = interest_svc.gain_formula(self.amount, age)

        if self.active:
            return gains

        return interest_svc.calculate_tax(gains - self.amount, age) if gains else 0
