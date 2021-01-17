import os
from uuid import uuid4

from django.contrib.auth.models import AbstractUser
from django.db import models


def make_path(_, filename):  # pragma: no cover -> no complexity
    filename_ext = os.path.splitext(filename)

    return f"{uuid4().hex}.{filename_ext}"


class CustomUser(AbstractUser):
    """
    User model that has the following attributes:
        - email
        - username
        - first_name
        - last_name
        - city
        - state
        - password
        - bio
        - profile_picture
        - friends

    REQUIRED_FIELDS = ["username", "first_name", "last_name", "city", "state"]
    USERNAME_FIELD = "email"
    """

    email = models.EmailField(unique=True)
    bio = models.CharField(max_length=250, null=True, blank=True)
    city = models.CharField(max_length=50)
    state = models.CharField(max_length=50)
    profile_picture = models.ImageField(null=True, blank=True, upload_to=make_path)
    friends = models.ManyToManyField("CustomUser", through="Friendship")

    REQUIRED_FIELDS = ["username", "first_name", "last_name", "city", "state"]
    USERNAME_FIELD = "email"


class Friendship(models.Model):
    """
    Friendship model that has the following attributes:
        - user1
        - user2
        - created_at
    """

    user1 = models.ForeignKey(
        "CustomUser", related_name="friendships1", related_query_name="friendship1", on_delete=models.CASCADE
    )
    user2 = models.ForeignKey(
        "CustomUser", related_name="friendships2", related_query_name="friendship2", on_delete=models.CASCADE
    )
    created_at = models.DateTimeField(auto_now_add=True)

    class Meta:
        unique_together = ["user1_id", "user2_id"]


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

    def get_invitation_type(self, type):
        type = type.upper()
        invitation_types = [type for type, _ in self.INVITATION_TYPE_CHOICES]

        if type not in invitation_types:
            raise AttributeError()

        return type

    class Meta:
        unique_together = ["type", "invitation_from", "invitation_to"]
