from rest_framework.serializers import ModelSerializer

from events.core.models import Event


class ListEventSerializer(ModelSerializer):
    class Meta:
        model = Event
        fields = ["id", "name"]


class DetailEventSerializer(ModelSerializer):
    class Meta:
        model = Event
        fields = ["id", "name", "date", "region"]
