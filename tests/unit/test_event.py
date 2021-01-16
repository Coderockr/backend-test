from django.test import TestCase

from events.core.models import Event


class EventTestCase(TestCase):
    """
    Test Event model that should has following attributes:
        - name
        - description
        - date
        - time
        - place
        - owner
        - is_active
        - participants
    """

    def test_model_has_attributes(self):
        self.assertEqual(hasattr(Event, "name"), True)
        self.assertEqual(hasattr(Event, "description"), True)
        self.assertEqual(hasattr(Event, "date"), True)
        self.assertEqual(hasattr(Event, "time"), True)
        self.assertEqual(hasattr(Event, "place"), True)
        self.assertEqual(hasattr(Event, "owner"), True)
        self.assertEqual(hasattr(Event, "is_active"), True)
        self.assertEqual(hasattr(Event, "participants"), True)
