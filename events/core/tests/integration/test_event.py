from django.urls import reverse
from model_bakery import baker
from rest_framework.test import APITestCase


class EventTest(APITestCase):
    def setUp(self):
        baker.make("Event", _quantity=15)

        return super().setUp()

    def test_list_events(self):
        client = self.client
        path = reverse("event-list")
        response = client.get(path)

        self.assertEqual(response.status_code, 200)
        self.assertEqual(response.data.get("count"), 15)
