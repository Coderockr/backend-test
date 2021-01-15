from django.test import TestCase

from events.core.models import CustomUser


class UserTest(TestCase):
    """
    Test Custom User model that should has following attributes:
        - first_name
        - last_name
        - email
        - password
        - bio
        - profile_picture
        - city
        - state
    """

    def test_model_has_attributes(self):
        self.assertEqual(hasattr(CustomUser, "first_name"), True)
        self.assertEqual(hasattr(CustomUser, "last_name"), True)
        self.assertEqual(hasattr(CustomUser, "email"), True)
        self.assertEqual(hasattr(CustomUser, "password"), True)
        self.assertEqual(hasattr(CustomUser, "bio"), True)
        self.assertEqual(hasattr(CustomUser, "profile_picture"), True)
        self.assertEqual(hasattr(CustomUser, "city"), True)
        self.assertEqual(hasattr(CustomUser, "state"), True)
