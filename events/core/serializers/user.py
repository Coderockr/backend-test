from rest_framework.serializers import ModelSerializer

from events.core.models import CustomUser


class ListUserSerializer(ModelSerializer):
    """
    Serializer to list users

    Has the following fields:
        - id

        - first_name

        - last_name

        - email
    """

    class Meta:
        model = CustomUser
        fields = ["id", "first_name", "last_name", "email"]
