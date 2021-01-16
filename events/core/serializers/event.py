from rest_framework.serializers import IntegerField, ModelSerializer, PrimaryKeyRelatedField

from events.core.models import Event
from events.core.models.user import CustomUser


class ListEventSerializer(ModelSerializer):
    """
    Serializer to list events

    Has the following fields:
        - id
        - name
    """

    class Meta:
        model = Event
        fields = ["id", "name"]


class DetailEventSerializer(ModelSerializer):
    """
    Serializer to detail events

    Has the following fields:
        - id
        - name
        - description
        - date
        - time
        - place
        - owner
        - is_active
        - participants
    """

    owner_id = IntegerField(source="owner.id")
    participants_id = PrimaryKeyRelatedField(source="participants", many=True, read_only=True)

    class Meta:
        model = Event
        fields = ["id", "name", "description", "date", "time", "place", "owner_id", "is_active", "participants_id"]


class CreateEventSerializer(ModelSerializer):
    """
    Serializer to create events

    Has the following fields:
        - name
        - description
        - date
        - time
        - place
    """

    class Meta:
        model = Event
        fields = ["name", "description", "date", "time", "place"]

    def create(self, validated_data):
        validated_data["owner"] = self.context.get("request").user

        return super().create(validated_data)


class UpdateEventSerializer(ModelSerializer):
    """
    Serializer to update events

    Has the following fields:
        - name
        - description
        - date
        - time
        - place
        - owner
        - is_active
        - participants
    """

    participants = PrimaryKeyRelatedField(many=True, queryset=CustomUser.objects.all())

    class Meta:
        model = Event
        fields = ["name", "description", "date", "time", "place", "owner", "is_active", "participants"]
