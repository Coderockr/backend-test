from rest_framework import mixins, viewsets
from rest_framework.decorators import action
from rest_framework.response import Response

from persons.models import Person
from persons.serializers import UserSerializer
from investments.serializers import InvestmentSerializer


class UserViewSet(
    mixins.CreateModelMixin,
    mixins.RetrieveModelMixin,
    mixins.UpdateModelMixin,
    mixins.ListModelMixin,
    viewsets.GenericViewSet):
    queryset = Person.objects.all()
    serializer_class = UserSerializer

    @action(detail=True, methods=['get'])
    def investments(self, request, pk=None):
        person = self.get_object()
        page = self.paginate_queryset(person.investments.all())
        if page is not None:
            serializer = InvestmentSerializer(page, many=True)
            return self.get_paginated_response(serializer.data)

        serializer = self.get_serializer(person.investments.all(), many=True)
        return Response(serializer.data)
        # return Response(serializer.data)