from django.db.models import (ExpressionWrapper, FloatField, DateField,
                              F, Case, When, Value)
from django.db.models.lookups import GreaterThan
from django.db.models.functions import ExtractMonth, Now
from django.conf import settings


INTEREST = settings.CODEROCKR_INTEREST


def calculate_gain(qs):
  qs = (
    qs.alias(
      diff_date=ExpressionWrapper(
        Now() - F('created_at'),
        output_field=DateField()
      ),
      months=ExtractMonth('diff_date'),
    )
    .annotate(
      balance=Case(
        When(
          GreaterThan(
            F('months'),
            0 
          ),
          then=_gain_formula()
        ),
        default=F('amount')
      )
    )
  )

  return qs





def _gain_formula():
  return ExpressionWrapper(
    F('amount') * (1+INTEREST) * F('months'),
    output_field=FloatField()
  )


def calculate_tax(gains, age):
  if age < 12:
    return _apply_tax(22.5)
  
  if age < 24:
    return _apply_tax(18.5)
  
  return _apply_tax(15)


def _apply_tax(gains, tax):
  return gains - (gains*tax/100)