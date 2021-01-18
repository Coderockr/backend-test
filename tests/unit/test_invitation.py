from django.test import TestCase

from events.core.models import Invitation


class InvitationTestCase(TestCase):
    """
    Test Invitation model that should has following attributes:
        - type
        - status
        - event
        - invitation_from
        - invitation_to
        - created_at
        - updated_at
    """

    def test_model_has_attributes(self):
        self.assertEqual(hasattr(Invitation, "type"), True)
        self.assertEqual(hasattr(Invitation, "status"), True)
        self.assertEqual(hasattr(Invitation, "event"), True)
        self.assertEqual(hasattr(Invitation, "invitation_from"), True)
        self.assertEqual(hasattr(Invitation, "invitation_to"), True)
        self.assertEqual(hasattr(Invitation, "created_at"), True)
        self.assertEqual(hasattr(Invitation, "updated_at"), True)
