from drf_spectacular.utils import inline_serializer
from rest_framework.serializers import CharField

bad_request_serializer = inline_serializer(name="bad_request", fields={"detail": CharField()})
forbidden_request_serializer = inline_serializer(name="forbbiden_request", fields={"detail": CharField()})
