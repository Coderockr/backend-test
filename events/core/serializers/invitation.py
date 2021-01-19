from rest_framework.relations import SlugRelatedField
from rest_framework.serializers import ModelSerializer, ValidationError

from events.core.models import Event, Invitation
from events.core.models.user import CustomUser
from events.core.signals.email import send_email_to_register


class ListInvitationSerializer(ModelSerializer):
    """
    Serializer to list invitations

    Has the following fields:
        - id

        - type

        - status

        - invitation_from

        - created_at
    """

    class Meta:
        model = Invitation
        fields = ["id", "type", "status", "invitation_from", "created_at"]


class CreateInvitationSerializer(ModelSerializer):
    """
    Serializer to create an invitation

    Has the following fields:

        - type

        - event

        - invitation_to
    """

    invitation_to = SlugRelatedField(
        slug_field="email", many=True, queryset=CustomUser.objects.all(), help_text="Email list"
    )

    def to_representation(self, invitations):
        return {
            "type": invitations[0].type if invitations else None,
            "event": invitations[0].event.id if invitations and invitations[0].event else None,
            "invitation_to": [instance.invitation_to.email for instance in invitations],
        }

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
        return value.upper()

    def validate_invitation_to(self, destinations):
        request = self.context.get("request")
        destination_emails = [destination.email for destination in destinations]

        if request.user.email in destination_emails:
            raise ValidationError(detail="Must not invite yourself.")

        return destinations

    # must not send friendship invitation with event
    def validate(self, attrs):
        if attrs.get("type") == "FS" and attrs.get("event"):
            raise ValidationError(detail="Sent friendship invitation with event??")

        return super().validate(attrs)

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
    """
    Serializer to update an invitation

    Has the following fields:
        - status
    """

    class Meta:
        model = Invitation
        fields = ["status"]

    def update(self, instance, validated_data):
        status = validated_data.get("status")
        request = self.context.get("request")

        # event invitation
        if instance.event:
            if status == Invitation.ACCEPTED:
                Event.objects.add_participant(instance.event, request.user)
            # elif status == Invitation.REJECTED:
            else:
                Event.objects.remove_participant(instance.event, request.user)

        return super().update(instance, validated_data)
