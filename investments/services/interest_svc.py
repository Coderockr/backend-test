from django.db.models import (ExpressionWrapper, FloatField, DateField,
                              F, Case, When, Q)
from django.db.models.lookups import GreaterThan
from django.db.models.functions import ExtractDay, Now
from django.conf import settings


INTEREST = settings.CODEROCKR_INTEREST


def calculate_gain(qs):
  qs = (
    qs.alias(
      diff_date=_diff_date(),
      days=ExtractDay('diff_date'),
    ).annotate(
      months=F('days')/30,
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


def _diff_date():
  def _diff(final_date=None):
    if not final_date:
      final_date = Now()

    return ExpressionWrapper(
      final_date - F('created_at'),
      output_field=DateField()
    )
  
  return Case(
    When(
      Q(withdrawn_at__isnull=True),
      then=_diff()
    ),
    default=_diff(F('withdrawn_at'))
  )


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