from datetime import date, datetime

from django.urls import reverse
from model_bakery import baker
from rest_framework.test import APITestCase

from events.core.models import Event
from events.core.tests.integration.setup import create_user_with_permission


class EventTest(APITestCase):
    def test_list_events(self):
        # setup
        baker.make("Event", _quantity=15)

        self.client
        path = reverse("event-list")
        response = self.client.get(path)

        self.assertEqual(response.status_code, 200)
        self.assertEqual(response.data.get("count"), 15)

    def test_retrieve_event(self):
        # setup
        baker.make("Event")
        event_id = 1
        event_database = Event.objects.get(pk=event_id)

        path = reverse("event-detail", args=[event_id])
        response = self.client.get(path)

        self.assertEqual(response.status_code, 200)
        self.assertEqual(response.data.get("id"), event_database.id)
        self.assertEqual(response.data.get("name"), event_database.name)
        self.assertEqual(response.data.get("description"), event_database.description)
        self.assertEqual(response.data.get("date"), str(event_database.date))
        self.assertEqual(response.data.get("time"), str(event_database.time))
        self.assertEqual(response.data.get("place"), event_database.place)

    def test_create_event(self):
        # setup
        new_user = create_user_with_permission(permissions=["core.add_event"])

        new_event = {
            "name": "somename",
            "description": "somedescription",
            "date": str(date.today()),
            "time": datetime.now().strftime("%H:%M:%S"),
            "place": "someplace",
        }

        # authenticate
        self.client.force_authenticate(new_user)

        path = reverse("event-list")
        response = self.client.post(path, new_event)

        self.assertEqual(response.status_code, 201)
        self.assertEqual(Event.objects.count(), 1)
