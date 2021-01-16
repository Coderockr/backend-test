from django.db import models


class Invitation(models.Model):
    # type
    EVENT = "EV"
    FRIENDSHIP = "FS"
    REGISTER = "RE"
    INVITATION_TYPE_CHOICES = [
        (EVENT, "Event"),
        (FRIENDSHIP, "Friendship"),
        (REGISTER, "Register"),
    ]
    # status
    ACCEPTED = "AC"
    REJECTED = "RE"
    PENDDING = "PE"
    INVITATION_STATUS_CHOICES = [
        (ACCEPTED, "Accepted"),
        (REJECTED, "Rejected"),
        (PENDDING, "Pendding"),
    ]

    type = models.CharField(max_length=2, choices=INVITATION_TYPE_CHOICES)
    status = models.CharField(max_length=2, choices=INVITATION_STATUS_CHOICES, default=PENDDING)
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
