from rest_framework.serializers import ModelSerializer

from events.core.models import Event


class ListEventSerializer(ModelSerializer):
    class Meta:
        model = Event
        fields = ["id", "name"]


class DetailEventSerializer(ModelSerializer):
    class Meta:
        model = Event
        fields = ["id", "name", "description", "date", "time", "place"]


class CreateEventSerializer(ModelSerializer):
    class Meta:
        model = Event
        fields = ["name", "description", "date", "time", "place"]
