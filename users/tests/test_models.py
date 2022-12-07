from django.test import TestCase

from users.models import User

class UserTestModel(TestCase):
    @classmethod
    def setUp(self):
        self.user_data = {
            "email":"teste@gmail.com",
            "username":"teste"
        }
        self.user = User.objects.create(**self.user_data)

    def test_username_max_length(self):
        max_length = self.user._meta.get_field('email').max_length

        self.assertEqual(max_length, 127)

    def test_email_can_be_null(self):
        nullable = self.user._meta.get_field("email").null

        self.assertFalse(nullable)