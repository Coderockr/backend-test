from django.db import models
from django.contrib.auth.models import AbstractUser
from django.conf import settings
from django.utils.translation import gettext_lazy as _


# Create your models here.
class User(AbstractUser):
  email = models.EmailField(_('email address'), unique=True)

  USERNAME_FIELD = 'email'
  REQUIRED_FIELDS = []
  ...


class Investment(models.Model):
  owner = models.ForeignKey(settings.AUTH_USER_MODEL, on_delete=models.CASCADE)
  amount = models.FloatField()
  active = models.BooleanField(default=True, null=False)
  created_at = models.DateTimeField()