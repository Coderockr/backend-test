from django.db import models


class Event(models.Model):
    name = models.CharField(max_length=100)
    description = models.TextField()
    date = models.DateField()
    time = models.TimeField()
    place = models.CharField(max_length=150)

    class Meta:
        ordering = ["name"]
