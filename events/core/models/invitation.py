from django.db import models


class InvitationQuerySet(models.QuerySet):
    def invitations_of_type(self, type):  # pragma: no cover -> not used yet
        return self.filter(type=type)


class InvitationManager(models.Manager):
    def get_queryset(self):
        return InvitationQuerySet(self.model, using=self._db)

    def get_invitations_of_type(self, type):  # pragma: no cover -> not used yet
        return self.get_queryset().invitations_of_type(type)


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

    type = models.CharField(max_length=2, choices=INVITATION_TYPE_CHOICES)
    status = models.CharField(max_length=2, choices=INVITATION_STATUS_CHOICES, default=PENDING)
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

    def get_invitation_type(self, type):
        # case insensitive
        type = dict(self.INVITATION_TYPE_CHOICES).get(type.upper())

        if type is None:
            raise AttributeError()

        return type

    class Meta:
        unique_together = ["type", "invitation_from", "invitation_to"]
        ordering = ["-created_at"]
