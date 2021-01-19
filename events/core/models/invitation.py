from django.db import models


class InvitationQuerySet(models.QuerySet):
    def already_exist(self, *, type, event=None, invitation_from, invitation_to_email):
        return self.filter(
            type=type, event=event, invitation_from=invitation_from, invitation_to__email=invitation_to_email
        ).exists()


class InvitationManager(models.Manager):
    def get_queryset(self):
        return InvitationQuerySet(self.model, using=self._db)

    def already_exist(self, *, type, event=None, invitation_from, invitation_to_email):
        return self.get_queryset().already_exist(
            type=type, event=event, invitation_from=invitation_from, invitation_to_email=invitation_to_email
        )


class Invitation(models.Model):
    """
    Invitation model that has the following attributes:
        - type
        - status
        - invitation_from
        - invitation_to
        - created_at
        - updated_at
    """

    # type
    EVENT = "EV"
    FRIENDSHIP = "FS"
    INVITATION_TYPE_CHOICES = [
        (EVENT, "Event"),
        (FRIENDSHIP, "Friendship"),
    ]
    # status
    ACCEPTED = "AC"
    REJECTED = "RE"
    PENDING = "PE"
    INVITATION_STATUS_CHOICES = [
        (ACCEPTED, "Accepted"),
        (REJECTED, "Rejected"),
        (PENDING, "Pending"),
    ]

    type = models.CharField(
        max_length=2, choices=INVITATION_TYPE_CHOICES, help_text="EV = Event Invitation | FS = Frienship Invitation"
    )
    status = models.CharField(
        max_length=2,
        choices=INVITATION_STATUS_CHOICES,
        default=PENDING,
        help_text="AC = Accepted | RE = Rejected | PE = Pending\n\nDefault is PE",
    )
    event = models.ForeignKey(
        "Event",
        related_name="invitations",
        related_query_name="invitation",
        on_delete=models.CASCADE,
        null=True,
        blank=True,
    )
    invitation_from = models.ForeignKey(
        "CustomUser",
        related_name="invitations_sended",
        related_query_name="invitation_sended",
        on_delete=models.CASCADE,
    )
    invitation_to = models.ForeignKey(
        "CustomUser",
        related_name="invitations_received",
        related_query_name="invitation_received",
        on_delete=models.CASCADE,
    )
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    objects = InvitationManager()

    class Meta:
        unique_together = ["type", "event", "invitation_from", "invitation_to"]
        ordering = ["-created_at"]
