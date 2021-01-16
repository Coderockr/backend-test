from rest_framework.serializers import ModelSerializer

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
    Serializer to detail events

    Has the following fields:
        - id
        - name
        - description
        - date
        - time
        - place
    """

    class Meta:
        model = Event
        fields = ["id", "name", "description", "date", "time", "place"]


class CreateEventSerializer(ModelSerializer):
    """
    Serializer to create events

    Has the following fields:
        - name
        - description
        - date
        - time
        - place
        - owner
    """

    class Meta:
        model = Event
        fields = ["name", "description", "date", "time", "place", "owner"]


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
    """

    class Meta:
        model = Event
        fields = ["name", "description", "date", "time", "place", "owner"]
