from django.db import models


class Event(models.Model):
    name = models.CharField(max_length=100)
    date = models.DateField()
    region = models.CharField(max_length=50)

    class Meta:
        ordering = ["name"]
