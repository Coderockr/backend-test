from django.db import models

# class EventQueryset(models.QuerySet):
#     def only_active(self):
#         return self.filter(active=True)


# class EventManager(models.Manager):
#     def get_queryset(self):
#         return EventQueryset(self.model, using=self._db).only_active()


class Event(models.Model):
    name = models.CharField(max_length=100)
    description = models.TextField()
    date = models.DateField()
    time = models.TimeField()
    place = models.CharField(max_length=150)
    owner = models.ForeignKey(
        "CustomUser", on_delete=models.CASCADE, related_name="events", related_query_name="event"
    )
    active = models.BooleanField(default=True)

    # objects = EventManager()

    class Meta:
        ordering = ["name"]
