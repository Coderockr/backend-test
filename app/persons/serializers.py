from rest_framework import serializers
from persons.models import Person


class PersonSerializer(serializers.ModelSerializer):
    class Meta:
        model = Person
        fields = ('id', 'email', 'name')
