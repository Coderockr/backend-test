from rest_framework.serializers import IntegerField, ModelSerializer, PrimaryKeyRelatedField

from events.core.models import Event


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
    Serializer to detail an event

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
    Serializer to create an event

    Has the following fields:

        - name

        - description

        - date

        - time

        - place
    """

    def create(self, validated_data):
        validated_data["owner"] = self.context.get("request").user

        return super().create(validated_data)

    class Meta:
        model = Event
        fields = ["name", "description", "date", "time", "place"]


class UpdateEventSerializer(ModelSerializer):
    """
    Serializer to update an event

    Has the following fields:
        - name

        - description

        - date

        - time

        - place

        - owner

        - is_active
    """

    class Meta:
        model = Event
        fields = ["name", "description", "date", "time", "place", "owner", "is_active"]
