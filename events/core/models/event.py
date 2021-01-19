from datetime import date, datetime

from django.db import models
from django.db.models import Q


class EventQueryset(models.QuerySet):
    def only_active(self):
        return self.filter(is_active=True)

    def own_events(self, owner):
        return self.filter(owner=owner)

    def participating_events(self, participant):
        return self.filter(participants=participant)

    def own_or_participating_events(self, user):
        return self.filter(Q(owner=user) | Q(participants=user)).distinct()

    def add_participant(self, event, user):
        if self.__event_is_open_to_participate(event) and not event.owner == user:
            event.participants.add(user)
            return True

    def remove_participant(self, event, user):
        if event.participants.filter(pk=user.id).exists():
            event.participants.remove(user)
            return True

    def __event_is_open_to_participate(self, event):
        date_now = date.today()
        time_now = datetime.now().strftime("%H:%M:%S")
        event_date = event.date
        event_time = event.time.strftime("%H:%M:%S")

        tomorrow_or_later = event_date > date_now
        today_and_later = event_date == date_now and event_time >= time_now
        if tomorrow_or_later or today_and_later:
            return True


class EventManager(models.Manager):
    def get_queryset(self):
        return EventQueryset(self.model, using=self._db)

    def get_only_active(self):
        return self.get_queryset().only_active()

    def get_own_or_participating_events(self, user):
        return self.get_queryset().own_or_participating_events(user)

    def add_participant(self, event, user):
        return self.get_queryset().add_participant(event, user)

    def remove_participant(self, event, user):
        return self.get_queryset().remove_participant(event, user)


class Event(models.Model):
    """
    Event model that has the following attributes:
        - name
        - description
        - date
        - time
        - place
        - owner
        - is_active
        - participants
    """

    name = models.CharField(max_length=100)
    description = models.TextField()
    date = models.DateField()
    time = models.TimeField()
    place = models.CharField(max_length=150)
    owner = models.ForeignKey(
        "CustomUser", on_delete=models.CASCADE, related_name="events", related_query_name="event"
    )
    is_active = models.BooleanField(default=True)
    participants = models.ManyToManyField(
        "CustomUser",
        related_name="participated_events",
        related_query_name="participated_event",
        through="Participants",
    )

    objects = EventManager()

    class Meta:
        ordering = ["name"]


class Participants(models.Model):
    event = models.ForeignKey(
        "Event", related_name="participations", related_query_name="participation", on_delete=models.CASCADE
    )
    user = models.ForeignKey(
        "CustomUser", related_name="participations", related_query_name="participation", on_delete=models.CASCADE
    )

    class Meta:
        unique_together = ["event", "user"]
