from model_bakery import baker
from rest_framework import status
from rest_framework.reverse import reverse
from rest_framework.test import APITestCase

from events.core.models import CustomUser, Invitation
from tests.integration.setup import add_friends, create_user_with_permission


class InvitationAPITestCase(APITestCase):
    def test_should_send_friend_invitation(self):
        # setup
        random_user_1 = baker.make(CustomUser)
        random_user_2 = baker.make(CustomUser)

        invitation_data = {
            "type": "FS",  # friendsip
            "invitation_to": [
                random_user_2.email,
            ],
        }

        path = reverse("invitation-list")

        # authenticate
        self.client.force_authenticate(user=random_user_1)

        # validation
        self.assertEqual(Invitation.objects.count(), 0)

        response = self.client.post(path, invitation_data)

        self.assertEqual(response.status_code, status.HTTP_201_CREATED)
        self.assertEqual(Invitation.objects.count(), 1)

    def test_should_receive_friend_request(self):
        # setup
        random_user_1 = baker.make(CustomUser)
        random_user_2 = baker.make(CustomUser)

        invitation_data = {
            "type": "FS",  # friendship
            "invitation_to": [
                random_user_2.email,
            ],
        }

        path = reverse("invitation-list")

        # authenticate
        self.client.force_authenticate(user=random_user_1)

        # validation
        self.assertEqual(random_user_2.invitations_received.count(), 0)

        response = self.client.post(path, invitation_data)

        self.assertEqual(response.status_code, status.HTTP_201_CREATED)
        self.assertEqual(random_user_2.invitations_received.count(), 1)

    def test_invitation_to_unregistered_should_not_be_created(self):
        # setup
        random_user_1 = baker.make(CustomUser)

        invitation_data = {
            "type": "EV",  # event
            "invitation_to": [
                "some_unregistered_email@email.com",
            ],
        }

        path = reverse("invitation-list")

        # authenticate
        self.client.force_authenticate(user=random_user_1)

        # validation
        response = self.client.post(path, invitation_data)

        self.assertEqual(response.status_code, status.HTTP_400_BAD_REQUEST)
        self.assertEqual(Invitation.objects.count(), 0)

    def test_should_send_invitation_just_to_registered_emails(self):
        # setup
        random_user_1 = baker.make(CustomUser)
        random_user_2 = baker.make(CustomUser)
        random_user_3 = baker.make(CustomUser)

        invitation_data = {
            "type": "FS",  # friendship
            "invitation_to": [
                random_user_2.email,
                "some_unregistered_email@email.com",
                random_user_3.email,
            ],
        }

        path = reverse("invitation-list")

        # authenticate
        self.client.force_authenticate(user=random_user_1)

        # validation
        response = self.client.post(path, invitation_data)

        self.assertEqual(response.status_code, status.HTTP_201_CREATED)
        self.assertEqual(Invitation.objects.count(), 2)

    def test_should_send_event_invitation_to_one_friend(self):
        # setup
        random_user_1 = baker.make(CustomUser)
        random_user_2 = baker.make(CustomUser)

        invitation_data = {
            "type": "EV",  # event
            "invitation_to": [
                random_user_2.email,
            ],
        }

        path = reverse("invitation-list")

        # authenticate
        self.client.force_authenticate(user=random_user_1)

        # validation
        response = self.client.post(path, invitation_data)

        self.assertEqual(response.status_code, status.HTTP_201_CREATED)
        self.assertEqual(Invitation.objects.count(), 1)

    def test_should_send_event_invitation_to_more_than_one_friend(self):
        # setup
        random_user_1 = baker.make(CustomUser)
        random_user_2 = baker.make(CustomUser)
        random_user_3 = baker.make(CustomUser)

        invitation_data = {
            "type": "EV",  # event
            "invitation_to": [
                random_user_2.email,
                random_user_3.email,
            ],
        }

        path = reverse("invitation-list")

        # authenticate
        self.client.force_authenticate(user=random_user_1)

        # validation
        response = self.client.post(path, invitation_data)

        self.assertEqual(response.status_code, status.HTTP_201_CREATED)
        self.assertEqual(Invitation.objects.count(), 2)

    def test_should_send_event_invitation_to_all_friends(self):
        # setup
        random_user_1 = baker.make(CustomUser)
        random_friends = baker.make(CustomUser, _quantity=10)

        add_friends(random_user_1, random_friends)

        invitation_data = {
            "type": "EV",  # event
            "invitation_to": "all_friends",
        }

        path = reverse("invitation-list")

        # authenticate
        self.client.force_authenticate(user=random_user_1)

        # validation
        response = self.client.post(path, invitation_data)

        self.assertEqual(response.status_code, status.HTTP_201_CREATED)
        self.assertEqual(Invitation.objects.count(), len(random_friends))

    def test_should_not_send_invite_to_yourself(self):
        # setup
        random_user = baker.make(CustomUser)

        invitation_data = {
            "type": "FS",  # friendship
            "invitation_to": [
                random_user.email,
            ],
        }

        path = reverse("invitation-list")

        # authenticate
        self.client.force_authenticate(user=random_user)

        # validation
        response = self.client.post(path, invitation_data)

        self.assertEqual(response.status_code, status.HTTP_400_BAD_REQUEST)
        self.assertEqual(Invitation.objects.count(), 0)

    def test_should_not_duplicate_invitation(self):
        # setup
        random_user_1 = baker.make(CustomUser)
        random_user_2 = baker.make(CustomUser)

        invitation_data = {
            "type": "FS",  # friendship
            "invitation_to": [random_user_2.email],
        }

        path = reverse("invitation-list")

        # authenticate
        self.client.force_authenticate(user=random_user_1)

        # validation
        self.client.post(path, invitation_data)

        # same invitation by second time
        response = self.client.post(path, invitation_data)

        self.assertEqual(response.status_code, status.HTTP_400_BAD_REQUEST)

        self.assertEqual(Invitation.objects.count(), 1)
        self.assertEqual(random_user_2.invitations_received.count(), 1)

    def test_user_that_has_permission_should_update_invitation(self):
        # setup
        new_user = create_user_with_permission(permissions=["core.change_invitation"])
        random_invitation = baker.make(Invitation)

        path = reverse("invitation-detail", args=[random_invitation.id])

        new_status = {
            "status": "AC",  # accepted
        }

        # authenticate
        self.client.force_authenticate(user=new_user)

        # validation update / put
        response = self.client.put(path, new_status)

        self.assertEqual(response.status_code, status.HTTP_200_OK)

        invitation_updated = Invitation.objects.get(pk=random_invitation.id)
        self.assertEqual(invitation_updated.status, new_status.get("status"))

        # validation update / patch
        new_status = {
            "status": "RE",  # rejected
        }
        response = self.client.patch(path, new_status)

        self.assertEqual(response.status_code, status.HTTP_200_OK)

        invitation_updated = Invitation.objects.get(pk=random_invitation.id)
        self.assertEqual(invitation_updated.status, new_status.get("status"))

    def test_user_that_received_invitation_should_update(self):
        # setup
        new_user = create_user_with_permission(permissions=[])
        random_invitation = baker.make(Invitation, invitation_to=new_user)

        path = reverse("invitation-detail", args=[random_invitation.id])

        new_status = {
            "status": "AC",  # accepted
        }

        # authenticate
        self.client.force_authenticate(user=new_user)

        # validation update / put
        response = self.client.put(path, new_status)

        self.assertEqual(response.status_code, status.HTTP_200_OK)

        invitation_updated = Invitation.objects.get(pk=random_invitation.id)
        self.assertEqual(invitation_updated.status, new_status.get("status"))

        # validation update / patch
        new_status = {
            "status": "RE",  # rejected
        }
        response = self.client.patch(path, new_status)

        self.assertEqual(response.status_code, status.HTTP_200_OK)

        invitation_updated = Invitation.objects.get(pk=random_invitation.id)
        self.assertEqual(invitation_updated.status, new_status.get("status"))

    def test_should_not_send_friendship_invitation_with_event(self):
        # setup
        random_user_1 = baker.make(CustomUser)
        random_user_2 = baker.make(CustomUser)
        random_event = baker.make("Event")

        invitation_data = {
            "type": "FS",  # friendship
            "event": random_event.id,
            "invitation_to": [random_user_2.email],
        }

        path = reverse("invitation-list")

        # authenticate
        self.client.force_authenticate(user=random_user_1)

        response = self.client.post(path, invitation_data)

        self.assertEqual(response.status_code, status.HTTP_400_BAD_REQUEST)
        self.assertEqual(Invitation.objects.all().count(), 0)

    def test_should_add_participant_when_accept_event_invitation(self):
        # setup
        random_user = baker.make(CustomUser)
        random_event = baker.make("Event")
        random_invitation = baker.make(Invitation, event=random_event, invitation_to=random_user)

        new_status = {
            "status": "AC",  # accepted
        }

        path = reverse("invitation-detail", args=[random_invitation.id])

        # authenticate
        self.client.force_authenticate(user=random_user)

        # validation
        response = self.client.put(path, new_status)

        self.assertEqual(response.status_code, status.HTTP_200_OK)
        self.assertEqual(random_event.participants.all().first(), random_user)
        self.assertEqual(random_event.participants.count(), 1)

    def test_should_remove_participant_when_reject_event_invitation(self):
        # setup
        random_user = baker.make(CustomUser)
        random_event = baker.make("Event")
        random_invitation = baker.make(Invitation, event=random_event, invitation_to=random_user)

        random_event.participants.add(random_user)

        new_status = {
            "status": "RE",  # rejected
        }

        path = reverse("invitation-detail", args=[random_invitation.id])

        # authenticate
        self.client.force_authenticate(user=random_user)

        # validation
        self.assertEqual(random_event.participants.count(), 1)

        response = self.client.put(path, new_status)

        self.assertEqual(response.status_code, status.HTTP_200_OK)
        self.assertEqual(random_event.participants.count(), 0)

    def test_should_do_nothing_when_reject_event_invitation_that_not_participate(self):
        # setup
        random_user = baker.make(CustomUser)
        random_event = baker.make("Event")
        random_invitation = baker.make(Invitation, event=random_event, invitation_to=random_user)

        new_status = {
            "status": "RE",  # rejected
        }

        path = reverse("invitation-detail", args=[random_invitation.id])

        # authenticate
        self.client.force_authenticate(user=random_user)

        # validation
        self.assertEqual(random_event.participants.count(), 0)

        response = self.client.put(path, new_status)

        self.assertEqual(response.status_code, status.HTTP_200_OK)
        self.assertEqual(random_event.participants.count(), 0)

    def test_user_that_received_invitation_should_delete(self):
        # setup
        random_user = baker.make(CustomUser)
        random_invitation = baker.make(Invitation, invitation_to=random_user)

        path = reverse("invitation-detail", args=[random_invitation.id])

        # authenticate
        self.client.force_authenticate(user=random_user)

        # validation
        response = self.client.delete(path)
        self.assertEqual(response.status_code, status.HTTP_204_NO_CONTENT)

    def test_user_that_send_invitation_should_delete(self):
        # setup
        random_user = baker.make(CustomUser)
        random_invitation = baker.make(Invitation, invitation_from=random_user)

        path = reverse("invitation-detail", args=[random_invitation.id])

        # authenticate
        self.client.force_authenticate(user=random_user)

        # validation
        response = self.client.delete(path)
        self.assertEqual(response.status_code, status.HTTP_204_NO_CONTENT)

    def test_user_that_has_permission_should_delete(self):
        # setup
        random_user = create_user_with_permission(permissions=["core.delete_invitation"])
        random_invitation = baker.make(Invitation)

        path = reverse("invitation-detail", args=[random_invitation.id])

        # authenticate
        self.client.force_authenticate(user=random_user)

        # validation
        response = self.client.delete(path)
        self.assertEqual(response.status_code, status.HTTP_204_NO_CONTENT)
