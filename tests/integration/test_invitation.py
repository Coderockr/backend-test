from model_bakery import baker
from rest_framework import status
from rest_framework.reverse import reverse
from rest_framework.test import APITestCase

from events.core.models import CustomUser, Invitation
from tests.integration.setup import create_user_with_permission


class InvitationAPITestCase(APITestCase):
    def test_send_invitation(self):
        # setup
        random_user_1 = baker.make(CustomUser)
        random_user_2 = baker.make(CustomUser)

        # invitation query params
        type = "ev"  # evento
        to = random_user_2.email

        path = reverse("invitation-send-invitation")
        path = path + f"?type={type}&to={to}"

        # authenticate
        self.client.force_authenticate(user=random_user_1)

        # validation
        response = self.client.get(path)

        self.assertEqual(response.status_code, status.HTTP_200_OK)

    def test_send_invite_to_yourself(self):
        # setup
        random_user = baker.make(CustomUser)

        # invitation query params
        type = "ev"  # evento
        to = random_user.email

        path = reverse("invitation-send-invitation")
        path = path + f"?type={type}&to={to}"

        # authenticate
        self.client.force_authenticate(user=random_user)

        # validation
        response = self.client.get(path)

        self.assertEqual(response.status_code, status.HTTP_400_BAD_REQUEST)

    def test_send_invitation_invalid_parameter(self):
        # setup
        random_user_1 = baker.make(CustomUser)
        random_user_2 = baker.make(CustomUser)

        # invitation query params
        type = "ev"  # evento
        to = random_user_2.email

        path = reverse("invitation-send-invitation")

        # wrong first param
        path = path + f"?wrong_param={type}&to={to}"

        # authenticate
        self.client.force_authenticate(user=random_user_1)

        # validation
        response = self.client.get(path)

        self.assertEqual(response.status_code, status.HTTP_400_BAD_REQUEST)

        # wrong second param
        path = path + f"?type={type}&wrong_param={to}"

        # authenticate
        self.client.force_authenticate(user=random_user_1)

        # validation
        response = self.client.get(path)

        self.assertEqual(response.status_code, status.HTTP_400_BAD_REQUEST)

    def test_send_invitation_invalid_type(self):
        # setup
        random_user_1 = baker.make(CustomUser)
        random_user_2 = baker.make(CustomUser)

        # invitation query params
        type = "somewrongtype"
        to = random_user_2.email

        path = reverse("invitation-send-invitation")
        path = path + f"?type={type}&to={to}"

        # authenticate
        self.client.force_authenticate(user=random_user_1)

        # validation
        response = self.client.get(path)

        self.assertEqual(response.status_code, status.HTTP_400_BAD_REQUEST)

    def test_send_same_invitation_more_than_once(self):
        # setup
        random_user_1 = baker.make(CustomUser)
        random_user_2 = baker.make(CustomUser)

        # invitation query params
        type = "ev"  # evento
        to = random_user_2.email

        path = reverse("invitation-send-invitation")
        path = path + f"?type={type}&to={to}"

        # authenticate
        self.client.force_authenticate(user=random_user_1)

        # validation
        response = self.client.get(path)

        self.assertEqual(response.status_code, status.HTTP_200_OK)

        # same invitation by second time
        response = self.client.get(path)

        self.assertEqual(response.status_code, status.HTTP_400_BAD_REQUEST)

    def test_send_email_to_register(self):
        # setup
        random_user = baker.make(CustomUser)

        # invitation query params
        type = "ev"  # evento
        # some unregistered email
        to = "some@email.com"

        path = reverse("invitation-send-invitation")
        path = path + f"?type={type}&to={to}"

        # authenticate
        self.client.force_authenticate(user=random_user)

        # validation
        response = self.client.get(path)

        self.assertEqual(response.status_code, status.HTTP_200_OK)

    def test_update_invitation(self):
        # setup
        new_user = create_user_with_permission(permissions=["core.change_invitation"])
        random_user = baker.make(CustomUser)
        random_invitation = baker.make(Invitation, invitation_from=new_user, invitation_to=random_user)

        path = reverse("invitation-detail", args=[random_invitation.id])

        new_status = {"status": "AC"}

        # authenticate
        self.client.force_authenticate(user=new_user)

        # validation update / put
        response = self.client.put(path, new_status)

        self.assertEqual(response.status_code, status.HTTP_200_OK)

        invitation_updated = Invitation.objects.get(pk=random_invitation.id)
        self.assertEqual(invitation_updated.status, new_status.get("status"))

        # validation update / patch
        new_status = {"status": "RE"}
        response = self.client.patch(path, new_status)

        self.assertEqual(response.status_code, status.HTTP_200_OK)

        invitation_updated = Invitation.objects.get(pk=random_invitation.id)
        self.assertEqual(invitation_updated.status, new_status.get("status"))
