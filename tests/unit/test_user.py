from django.test import TestCase

from events.core.models import CustomUser
from events.core.models.user import Invitation


class UserTestCase(TestCase):
    """
    Test Custom User model that should has following attributes:
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
    """

    def test_model_has_attributes(self):
        self.assertEqual(hasattr(CustomUser, "email"), True)
        self.assertEqual(hasattr(CustomUser, "username"), True)
        self.assertEqual(hasattr(CustomUser, "first_name"), True)
        self.assertEqual(hasattr(CustomUser, "last_name"), True)
        self.assertEqual(hasattr(CustomUser, "city"), True)
        self.assertEqual(hasattr(CustomUser, "state"), True)
        self.assertEqual(hasattr(CustomUser, "password"), True)
        self.assertEqual(hasattr(CustomUser, "bio"), True)
        self.assertEqual(hasattr(CustomUser, "profile_picture"), True)
        self.assertEqual(hasattr(CustomUser, "friends"), True)


class InvitationTestCase(TestCase):
    """
    Test Invitation model that should has following attributes:
        - type
        - status
        - invitation_from
        - invitation_to
        - created_at
        - updated_at
    """

    def test_model_has_attributes(self):
        self.assertEqual(hasattr(Invitation, "type"), True)
        self.assertEqual(hasattr(Invitation, "status"), True)
        self.assertEqual(hasattr(Invitation, "invitation_from"), True)
        self.assertEqual(hasattr(Invitation, "invitation_to"), True)
        self.assertEqual(hasattr(Invitation, "created_at"), True)
        self.assertEqual(hasattr(Invitation, "updated_at"), True)
