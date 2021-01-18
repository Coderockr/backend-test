from rest_framework.relations import SlugRelatedField
from rest_framework.serializers import ModelSerializer, ValidationError

from events.core.models import Invitation
from events.core.models.user import CustomUser
from events.core.signals.email import send_email_to_register


class ListInvitationSerializer(ModelSerializer):
    class Meta:
        model = Invitation
        fields = ["id", "type", "status", "invitation_from", "created_at"]


class CreateInvitationSerializer(ModelSerializer):
    invitation_to = SlugRelatedField(slug_field="email", many=True, queryset=CustomUser.objects.all())

    def to_representation(self, instances):
        return {
            "type": instances[0].type if instances else None,
            "event": instances[0].event if instances else None,
            "invitation_to": [instance.invitation_to.email for instance in instances],
        }

    # URGENT ->> type = FS and event is not None should not be possible
    # URGENT ->> type = FS and event is not None should not be possible
    # URGENT ->> type = FS and event is not None should not be possible
    # URGENT ->> type = FS and event is not None should not be possible
    # URGENT ->> type = FS and event is not None should not be possible
    # URGENT ->> type = FS and event is not None should not be possible
    # URGENT ->> type = FS and event is not None should not be possible
    # URGENT ->> type = FS and event is not None should not be possible
    # URGENT ->> type = FS and event is not None should not be possible

    def to_internal_value(self, data):
        invitation_to = data.pop("invitation_to", [])
        unregistered_emails = [
            destination for destination in invitation_to if CustomUser.objects.is_unregistered(destination)
        ]
        registered_emails = [
            destination for destination in invitation_to if not CustomUser.objects.is_unregistered(destination)
        ]

        if invitation_to and invitation_to == "all_friends":
            request = self.context.get("request")
            all_friends_email = [friend.email for friend in CustomUser.objects.get_all_friends(request.user)]

            data["invitation_to"] = all_friends_email

        # must create invitation just to registered emails
        # unregistered emails must receive an register invitation email
        elif unregistered_emails:
            for email in unregistered_emails:
                send_email_to_register(email)

            data["invitation_to"] = registered_emails

        else:
            data["invitation_to"] = invitation_to

        return super().to_internal_value(data)

    # must permit case insensitive
    def validate_type(self, value):
        return value.lower()

    def validate_invitation_to(self, destinations):
        request = self.context.get("request")
        destination_emails = [destination.email for destination in destinations]

        if request.user.email in destination_emails:
            raise ValidationError(detail="Must not invite yourself.")

        return destinations

    def create(self, validated_data):
        request = self.context.get("request")
        destinations = validated_data.pop("invitation_to")

        result = [
            # must not duplicate invitation
            Invitation.objects.create(**validated_data, invitation_from=request.user, invitation_to=destination)
            for destination in destinations
            if not Invitation.objects.already_exist(
                **validated_data,
                invitation_from=request.user,
                invitation_to_email=destination,
            )
        ]

        return result

    class Meta:
        model = Invitation
        fields = ["type", "event", "invitation_to"]


class UpdateInvitationSerializer(ModelSerializer):
    class Meta:
        model = Invitation
        fields = ["status"]
