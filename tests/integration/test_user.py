from djoser.email import ActivationEmail
from model_bakery import baker
from rest_framework import status
from rest_framework.reverse import reverse
from rest_framework.test import APITestCase

from events.core.models.invitation import Invitation
from events.core.models.user import CustomUser
from tests.integration.setup import add_friends, add_user_to_participate_in_events, create_user_with_permission


class UserAPITestCase(APITestCase):
    # events that user is owner or is participating
    def test_should_return_all_my_events(self):
        # setup
        new_user = create_user_with_permission(permissions=[])
        # random events that user is not owner and is not participating
        baker.make("Event", _quantity=5)

        random_owner_events = baker.make("Event", _quantity=3, owner=new_user)

        random_participating_events = baker.make("Event", _quantity=7)
        add_user_to_participate_in_events(random_participating_events, new_user)

        path = reverse("user-my-events")

        # authenticate
        self.client.force_authenticate(user=new_user)

        # validation
        response = self.client.get(path)

        self.assertEqual(response.status_code, status.HTTP_200_OK)
        self.assertEqual(response.data.get("count"), len(random_owner_events) + len(random_participating_events))

        # should has the serializer fields
        self.assertNotEqual(response.data.get("results")[0].get("id"), None)
        self.assertNotEqual(response.data.get("results")[0].get("name"), None)

    def test_should_return_just_my_own_events(self):
        # setup
        new_user = create_user_with_permission(permissions=[])
        # random events that user is not owner
        baker.make("Event", _quantity=5)

        random_owner_events = baker.make("Event", _quantity=3, owner=new_user)

        path = reverse("user-my-events") + "?owner=true"

        # authenticate
        self.client.force_authenticate(user=new_user)

        # validation
        response = self.client.get(path)

        self.assertEqual(response.status_code, status.HTTP_200_OK)
        self.assertEqual(response.data.get("count"), len(random_owner_events))

        # should has the serializer fields
        self.assertNotEqual(response.data.get("results")[0].get("id"), None)
        self.assertNotEqual(response.data.get("results")[0].get("name"), None)

    def test_should_return_just_participating_events(self):
        # setup
        new_user = create_user_with_permission(permissions=[])
        # random events that user is owner
        baker.make("Event", _quantity=3, owner=new_user)

        random_participating_events = baker.make("Event", _quantity=5)
        add_user_to_participate_in_events(random_participating_events, new_user)

        path = reverse("user-my-events") + "?participating=true"

        # authenticate
        self.client.force_authenticate(user=new_user)

        # validation
        response = self.client.get(path)

        self.assertEqual(response.status_code, status.HTTP_200_OK)
        self.assertEqual(response.data.get("count"), len(random_participating_events))

        # should has the serializer fields
        self.assertNotEqual(response.data.get("results")[0].get("id"), None)
        self.assertNotEqual(response.data.get("results")[0].get("name"), None)

    def test_should_activate_account(self):
        # setup
        new_user = create_user_with_permission(permissions=[])
        new_user.is_active = False
        new_user.save()

        context_data = ActivationEmail(context={"user": new_user}).get_context_data()
        uid = context_data.get("uid")
        token = context_data.get("token")

        path = reverse("user-account-activation", args=[uid, token])

        # validation
        self.assertEqual(CustomUser.objects.get(pk=new_user.id).is_active, False)

        response = self.client.get(path)

        self.assertEqual(response.status_code, status.HTTP_204_NO_CONTENT)
        self.assertEqual(CustomUser.objects.get(pk=new_user.id).is_active, True)

    def test_should_return_friends(self):
        # setup
        new_user = create_user_with_permission(permissions=[])
        random_users = baker.make(CustomUser, _quantity=15)

        add_friends(new_user, random_users)

        path = reverse("user-friends")

        # authenticate
        self.client.force_authenticate(new_user)

        # validation
        response = self.client.get(path)

        self.assertEqual(response.status_code, status.HTTP_200_OK)
        self.assertEqual(response.data.get("count"), len(random_users))

        # should has the serializer fields
        self.assertNotEqual(response.data.get("results")[0].get("id"), None)
        self.assertNotEqual(response.data.get("results")[0].get("first_name"), None)
        self.assertNotEqual(response.data.get("results")[0].get("last_name"), None)
        self.assertNotEqual(response.data.get("results")[0].get("email"), None)

    def test_should_return_friendship_invitations(self):
        # setup
        new_user = create_user_with_permission(permissions=[])
        random_friend_invitations = baker.make(
            Invitation, invitation_to=new_user, type=Invitation.FRIENDSHIP, _quantity=15
        )

        path = reverse("user-friendship-invitations")

        # authenticate
        self.client.force_authenticate(user=new_user)

        # validation
        response = self.client.get(path)
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        self.assertEqual(response.data.get("count"), len(random_friend_invitations))

        # should has the serializer fields
        self.assertNotEqual(response.data.get("results")[0].get("id"), None)
        self.assertNotEqual(response.data.get("results")[0].get("type"), None)
        self.assertNotEqual(response.data.get("results")[0].get("status"), None)
        self.assertNotEqual(response.data.get("results")[0].get("invitation_from"), None)
        self.assertNotEqual(response.data.get("results")[0].get("created_at"), None)

    def test_should_return_event_invitations(self):
        # setup
        new_user = create_user_with_permission(permissions=[])
        random_event_invitations = baker.make(
            Invitation,
            invitation_to=new_user,
            type=Invitation.EVENT,
            _quantity=15,
        )

        path = reverse("user-event-invitations")

        # authenticate
        self.client.force_authenticate(user=new_user)

        # validation
        response = self.client.get(path)
        self.assertEqual(response.status_code, status.HTTP_200_OK)
        self.assertEqual(response.data.get("count"), len(random_event_invitations))

        # should has the serializer fields
        self.assertNotEqual(response.data.get("results")[0].get("id"), None)
        self.assertNotEqual(response.data.get("results")[0].get("type"), None)
        self.assertNotEqual(response.data.get("results")[0].get("status"), None)
        self.assertNotEqual(response.data.get("results")[0].get("invitation_from"), None)
        self.assertNotEqual(response.data.get("results")[0].get("created_at"), None)

    def test_should_remove_friend(self):
        # setup
        new_user = create_user_with_permission(permissions=[])
        random_friend = baker.make(CustomUser)

        new_user.friends.add(random_friend)

        path = reverse("user-remove-friend", args=[random_friend.id])

        # authenticate
        self.client.force_authenticate(user=new_user)

        # validation
        self.assertEqual(new_user.friends.count(), 1)

        response = self.client.delete(path)
        self.assertEqual(response.status_code, status.HTTP_204_NO_CONTENT)
        self.assertEqual(new_user.friends.count(), 0)
