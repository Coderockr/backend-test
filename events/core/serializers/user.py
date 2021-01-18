from rest_framework.serializers import ModelSerializer

from events.core.models import CustomUser


class ListUserSerializer(ModelSerializer):
    class Meta:
        model = CustomUser
        fields = ["id", "first_name", "last_name", "email"]
