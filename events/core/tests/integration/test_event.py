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
        path = reverse("event-list")

        # validation
        response = self.client.get(path)

        self.assertEqual(response.status_code, 200)
        self.assertEqual(response.data.get("count"), 15)

    def test_retrieve_event(self):
        # setup
        random_event = baker.make("Event")
        path = reverse("event-detail", args=[random_event.id])

        # validation
        response = self.client.get(path)

        self.assertEqual(response.status_code, 200)
        self.assertEqual(response.data.get("id"), random_event.id)
        self.assertEqual(response.data.get("name"), random_event.name)
        self.assertEqual(response.data.get("description"), random_event.description)
        self.assertEqual(response.data.get("date"), str(random_event.date))
        self.assertEqual(response.data.get("time"), str(random_event.time))
        self.assertEqual(response.data.get("place"), random_event.place)

    def test_create_event(self):
        # setup
        new_user = create_user_with_permission(permissions=["core.add_event"])
        new_event = {
            "name": "somename",
            "description": "somedescription",
            "date": str(date.today()),
            "time": datetime.now().strftime("%H:%M:%S"),
            "place": "someplace",
            "owner": new_user.id,
        }
        path = reverse("event-list")

        # authenticate
        self.client.force_authenticate(user=new_user)

        # validation
        response = self.client.post(path, new_event)

        self.assertEqual(response.status_code, 201)
        self.assertEqual(Event.objects.count(), 1)

    def test_user_can_update_own_event(self):
        """
        Test whether the user can update an event that he is own
        """
        # setup
        new_user = create_user_with_permission(permissions=[])
        random_event = baker.make("Event", owner=new_user)

        new_name = "somenewname"
        update_event_data = {
            "name": new_name,
            "description": random_event.description,
            "date": random_event.date,
            "time": random_event.time,
            "place": random_event.place,
            "active": random_event.active,
            "owner": random_event.owner.id,
        }
        path = reverse("event-detail", args=[random_event.id])

        # authenticate
        self.client.force_authenticate(user=new_user)

        # validation update / put
        response = self.client.put(path, update_event_data)

        updated_event = Event.objects.get(pk=random_event.id)
        self.assertEqual(response.status_code, 200)
        self.assertEqual(updated_event.name, new_name)

        # validation partial_update / patch
        new_name = "somenewnameagain"
        response = self.client.patch(path, {"name": new_name})

        updated_event = Event.objects.get(pk=random_event.id)
        self.assertEqual(response.status_code, 200)
        self.assertEqual(updated_event.name, new_name)

    def test_user_cannot_update_non_owner_event(self):
        """
        Test whether the user can update an event that he is not own
        """
        # setup
        new_user = create_user_with_permission(permissions=[])
        random_event = baker.make("Event")
        path = reverse("event-detail", args=[random_event.id])

        # authenticate
        self.client.force_authenticate(user=new_user)

        # validation update / put
        response = self.client.put(path)

        self.assertEqual(response.status_code, 403)

        # validation partial_update / patch
        response = self.client.patch(path)

        self.assertEqual(response.status_code, 403)

    def test_user_can_cancel_own_event(self):
        """
        Test whether the user can cancel an event that he is own
        """
        # setup
        new_user = create_user_with_permission(permissions=[])
        random_event = baker.make("Event", owner=new_user)
        path = reverse("event-detail", args=[random_event.id])

        # authenticate
        self.client.force_authenticate(user=new_user)

        # validation
        response = self.client.delete(path)

        self.assertEqual(response.status_code, 204)

    def test_user_cannot_cancel_non_owner_event(self):
        """
        Test whether the user can cancel an event that he is not own
        """
        # setup
        new_user = create_user_with_permission(permissions=[])
        random_event = baker.make("Event")
        path = reverse("event-detail", args=[random_event.id])

        # authenticate
        self.client.force_authenticate(user=new_user)

        # validation
        response = self.client.delete(path)

        self.assertNotEqual(random_event.owner, new_user)
        self.assertEqual(response.status_code, 403)
