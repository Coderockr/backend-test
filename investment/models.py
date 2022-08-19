from django.db import models
from decimal import Decimal
from django.utils.translation import gettext_lazy as _
from datetime import date, timedelta
from django.core.validators import MinValueValidator, MaxValueValidator
import datetime

class Owner(models.Model):
    name = models.CharField(
        max_length=255, verbose_name=_("name")
    )

class Investments(models.Model):
    owner = models.ForeignKey(
        'Owner', on_delete=models.CASCADE, verbose_name=_("owner"), related_name="owner"
    )
    creation_date = models.DateField(
        verbose_name=_("created in"), validators=[MaxValueValidator(datetime.date.today)]
    )
    update_date = models.DateField(
        verbose_name=_("updated"), null=True, blank=True
    )
    amount = models.DecimalField(
        max_digits=19, decimal_places=10, verbose_name=_("amount"), validators=[MinValueValidator(0)]
    )
    income = models.DecimalField(
        max_digits=19, decimal_places=10, null=True, blank=True, verbose_name=_("income"), validators=[MinValueValidator(0)]
    )
    balance = models.DecimalField(
        max_digits=19, decimal_places=10, null=True, blank=True, verbose_name=_("balance"), validators=[MinValueValidator(0)]
    )
    withdrawal_date = models.DateField(
        verbose_name=_("withdrawal date"), null=True, blank=True
    )

    @staticmethod
    def calc_investment_date(update_date):
        today = date.today()
        days = (today - update_date).days
        months_in_days = days - (days % 30)
        new_update_date = update_date + datetime.timedelta(days=months_in_days)
        months = days // 30
        return months, new_update_date

    @property
    def expected_balance(self):
        if not self.withdrawal_date: 
            calc = self.calc_investment_date(self.update_date)
            gain = (self.balance*(1 + Decimal(0.0052))**calc[0]) - self.balance
            income = self.income + gain if self.income else gain
            balance = self.amount + income
            id = self.id
            Investments.objects.filter(pk=id).update(income=income)
            Investments.objects.filter(pk=id).update(balance=balance)
            Investments.objects.filter(pk=id).update(update_date=calc[1])
            value = balance*(1+Decimal(0.0052))**1
        else:
            value = {}
        
        return value