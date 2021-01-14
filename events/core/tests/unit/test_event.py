from django.test import TestCase

from events.core.models import Event


class EventTest(TestCase):
    def test_model_has_attributes(self):
        self.assertEqual(hasattr(Event, "name"), True)
        self.assertEqual(hasattr(Event, "date"), True)
        self.assertEqual(hasattr(Event, "region"), True)
