from rest_framework.serializers import ModelSerializer

from events.core.models import Invitation


class UpdateInvitationSerializer(ModelSerializer):
    class Meta:
        model = Invitation
        fields = ["status"]
