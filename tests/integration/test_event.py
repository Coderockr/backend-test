from datetime import date, datetime, timedelta

from model_bakery import baker
from rest_framework import status
from rest_framework.reverse import reverse
from rest_framework.test import APITestCase

from events.core.models import Event
from tests.integration.setup import create_user_with_permission


class EventAPITestCase(APITestCase):
    def test_should_list_events(self):
        # setup
        random_events = baker.make("Event", _quantity=15)

        # random events that not must returned
        baker.make("Event", is_active=False, _quantity=10)

        path = reverse("event-list")

        # validation
        response = self.client.get(path)

        self.assertEqual(response.status_code, status.HTTP_200_OK)
        self.assertEqual(response.data.get("count"), len(random_events))

        first_result = response.data.get("results")[0]

        # should has the serializer fields
        self.assertNotEqual(first_result.get("id"), None)
        self.assertNotEqual(first_result.get("name"), None)

    def test_should_retrieve_event(self):
        # setup
        random_event = baker.make("Event")

        path = reverse("event-detail", args=[random_event.id])

        # validation
        response = self.client.get(path)

        self.assertEqual(response.status_code, status.HTTP_200_OK)

        # should has the serializer fields and their corresponding values
        self.assertEqual(response.data.get("id"), random_event.id)
        self.assertEqual(response.data.get("name"), random_event.name)
        self.assertEqual(response.data.get("description"), random_event.description)
        self.assertEqual(response.data.get("date"), str(random_event.date))
        self.assertEqual(response.data.get("time"), str(random_event.time))
        self.assertEqual(response.data.get("place"), random_event.place)
        self.assertEqual(response.data.get("owner_id"), random_event.owner.id)
        self.assertEqual(response.data.get("is_active"), random_event.is_active)

        participants_id = [participant.id for participant in random_event.participants.all()]
        self.assertEqual(response.data.get("participants_id"), participants_id)

    def test_should_create_event(self):
        # setup
        new_user = create_user_with_permission(permissions=["core.add_event"])
        new_event = {
            "name": "somename",
            "description": "somedescription",
            "date": str(date.today()),
            "time": datetime.now().strftime("%H:%M:%S"),
            "place": "someplace",
        }

        path = reverse("event-list")

        # authenticate
        self.client.force_authenticate(user=new_user)

        # validation
        response = self.client.post(path, new_event)

        self.assertEqual(response.status_code, status.HTTP_201_CREATED)
        self.assertEqual(Event.objects.count(), 1)
        self.assertEqual(Event.objects.all().first().owner, new_user)

    def test_should_user_can_update_own_event(self):
        """
        Test whether the user can update an event that he is own
        """
        # setup
        new_user = baker.make("CustomUser")
        random_event = baker.make("Event", owner=new_user)

        new_name = "somenewname"
        update_event_data = {
            "name": new_name,
            "description": random_event.description,
            "date": random_event.date,
            "time": random_event.time,
            "place": random_event.place,
            "owner": random_event.owner.id,
            "is_active": random_event.is_active,
            "participants": [],
        }

        path = reverse("event-detail", args=[random_event.id])

        # authenticate
        self.client.force_authenticate(user=new_user)

        # validation update / put
        response = self.client.put(path, update_event_data)

        self.assertEqual(response.status_code, status.HTTP_200_OK)

        updated_event = Event.objects.get(pk=random_event.id)
        self.assertEqual(updated_event.name, new_name)

        # validation partial_update / patch
        new_name = "somenewnameagain"
        response = self.client.patch(path, {"name": new_name})

        self.assertEqual(response.status_code, status.HTTP_200_OK)

        updated_event = Event.objects.get(pk=random_event.id)
        self.assertEqual(updated_event.name, new_name)

    def test_user_should_not_update_non_owner_event(self):
        """
        Test whether the user can update an event that he is not own
        """
        # setup
        new_user = baker.make("CustomUser")
        random_event = baker.make("Event")

        path = reverse("event-detail", args=[random_event.id])

        # authenticate
        self.client.force_authenticate(user=new_user)

        # validation update / put
        response = self.client.put(path)

        self.assertEqual(response.status_code, status.HTTP_403_FORBIDDEN)

        # validation partial_update / patch
        response = self.client.patch(path)

        self.assertEqual(response.status_code, status.HTTP_403_FORBIDDEN)

    def test_user_should_cancel_own_event(self):
        """
        Test whether the user can cancel an event that he is own
        """
        # setup
        new_user = baker.make("CustomUser")
        random_event = baker.make("Event", owner=new_user)

        path = reverse("event-detail", args=[random_event.id])

        # authenticate
        self.client.force_authenticate(user=new_user)

        # validation
        response = self.client.delete(path)

        self.assertEqual(response.status_code, status.HTTP_204_NO_CONTENT)

    def test_user_should_not_cancel_non_owner_event(self):
        """
        Test whether the user can cancel an event that he is not own
        """
        # setup
        new_user = baker.make("CustomUser")
        random_event = baker.make("Event")
        path = reverse("event-detail", args=[random_event.id])

        # authenticate
        self.client.force_authenticate(user=new_user)

        # validation
        response = self.client.delete(path)

        self.assertNotEqual(random_event.owner, new_user)
        self.assertEqual(response.status_code, status.HTTP_403_FORBIDDEN)

    def test_should_add_participant_to_event(self):
        random_owner = baker.make("CustomUser")
        random_event = baker.make(
            Event,
            date=date.today(),
            time=datetime.now().time(),
            owner=random_owner,
        )
        random_participant = baker.make("CustomUser")

        path = reverse("event-add-participant", args=[random_event.id, random_participant.id])

        # authenticate
        self.client.force_authenticate(user=random_owner)

        # validation
        self.assertEqual(random_event.participants.count(), 0)

        response = self.client.post(path)
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        self.assertEqual(random_event.participants.count(), 1)

    def test_user_should_not_participate_own_event(self):
        random_owner = baker.make("CustomUser")
        random_event = baker.make(
            Event,
            date=date.today(),
            time=datetime.now().time(),
            owner=random_owner,
        )

        path = reverse("event-add-participant", args=[random_event.id, random_owner.id])

        # authenticate
        self.client.force_authenticate(user=random_owner)

        # validation
        self.assertEqual(random_event.participants.count(), 0)

        response = self.client.post(path)
        self.assertEqual(response.status_code, status.HTTP_400_BAD_REQUEST)
        self.assertEqual(random_event.participants.count(), 0)

    def test_user_should_not_participate_event_that_already_occurred(self):
        random_owner = baker.make("CustomUser")
        random_event = baker.make(
            Event,
            date=date.today(),
            time=(datetime.now() - timedelta(minutes=1)).time(),
            owner=random_owner,
        )
        random_participant = baker.make("CustomUser")

        path = reverse("event-add-participant", args=[random_event.id, random_participant.id])

        # authenticate
        self.client.force_authenticate(user=random_owner)

        # validation
        self.assertEqual(random_event.participants.count(), 0)

        response = self.client.post(path)
        self.assertEqual(response.status_code, status.HTTP_400_BAD_REQUEST)
        self.assertEqual(random_event.participants.count(), 0)

    def test_should_remove_participant_from_event(self):
        # setup
        random_user = baker.make("CustomUser")
        random_event = baker.make(Event, owner=random_user)

        random_event.participants.add(random_user)

        path = reverse("event-remove-participant", args=[random_event.id, random_user.id])

        # authenticate
        self.client.force_authenticate(user=random_user)

        # validation
        self.assertEqual(random_event.participants.count(), 1)

        response = self.client.post(path)
        self.assertEqual(response.status_code, status.HTTP_204_NO_CONTENT)
        self.assertEqual(random_event.participants.count(), 0)

    def test_should_not_remove_non_participant_from_event(self):
        # setup
        random_user = baker.make("CustomUser")
        random_event = baker.make(Event, owner=random_user)

        path = reverse("event-remove-participant", args=[random_event.id, random_user.id])

        # authenticate
        self.client.force_authenticate(user=random_user)

        # validation
        self.assertEqual(random_event.participants.count(), 0)

        response = self.client.post(path)
        self.assertEqual(response.status_code, status.HTTP_400_BAD_REQUEST)
        self.assertEqual(random_event.participants.count(), 0)
