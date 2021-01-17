from rest_framework.serializers import ModelSerializer

from events.core.models import Invitation


class ListInvitationSerializer(ModelSerializer):
    class Meta:
        model = Invitation
        fields = ["type", "status", "invitation_from", "created_at"]


class UpdateInvitationSerializer(ModelSerializer):
    class Meta:
        model = Invitation
        fields = ["status"]
