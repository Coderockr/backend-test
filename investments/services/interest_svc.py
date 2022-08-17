import decimal
import math
from django.conf import settings


INTEREST = settings.CODEROCKR_INTEREST


def gain_formula(amount, months):
    return amount * decimal.Decimal(math.pow((1 + INTEREST), months))


def calculate_tax(gains, age):
    if age < 12:
        return _apply_tax(gains, 22.5)

    if age < 24:
        return _apply_tax(gains, 18.5)

    return _apply_tax(gains, 15)


def _apply_tax(gains, tax):
    return gains - (gains * decimal.Decimal(tax) / 100)
