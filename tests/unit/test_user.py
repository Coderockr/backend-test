from django.test import TestCase

from events.core.models import CustomUser


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
