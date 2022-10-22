from investments.serializers import InvestmentSerializer
from rest_framework import mixins, viewsets
from rest_framework.decorators import action
from rest_framework.response import Response

from persons.models import Person
from persons.serializers import PersonSerializer


class PersonViewSet(
    mixins.CreateModelMixin,
    mixins.RetrieveModelMixin,
    mixins.ListModelMixin,
    viewsets.GenericViewSet):
    queryset = Person.objects.all().order_by('-id')
    serializer_class = PersonSerializer

    @action(detail=True, methods=['get'])
    def investments(self, request, pk=None, *args, **kwargs):
        person = self.get_object()
        page = self.paginate_queryset(person.investments.all().order_by('-id'))
        if page is not None:
            serializer = InvestmentSerializer(page, many=True)
            return self.get_paginated_response(serializer.data)

        serializer = self.get_serializer(person.investments.all(), many=True)
        return Response(serializer.data)
