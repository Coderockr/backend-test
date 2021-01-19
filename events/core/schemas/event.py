from drf_spectacular.utils import extend_schema, inline_serializer
from rest_framework.serializers import CharField

from events.core.serializers import CreateEventSerializer, UpdateEventSerializer

bad_request_serializer = inline_serializer(name="bad_request", fields={"detail": CharField()})
forbidden_request_serializer = inline_serializer(name="forbbiden_request", fields={"detail": CharField()})

LIST_SCHEMA = extend_schema(description="list all events")
RETRIEVE_SCHEMA = extend_schema(description="retrieve an event")

PARTIAL_UPDATE_SCHEMA = extend_schema(
    description="update an event",
    responses={
        "201": UpdateEventSerializer,
        "400": bad_request_serializer,
        "403": forbidden_request_serializer,
    },
)

UPDATE_SCHEMA = extend_schema(
    description="update an event",
    responses={
        "201": UpdateEventSerializer,
        "400": bad_request_serializer,
        "403": forbidden_request_serializer,
    },
)

CREATE_SCHEMA = extend_schema(
    description="create an event",
    responses={
        "201": CreateEventSerializer,
        "400": bad_request_serializer,
        "403": forbidden_request_serializer,
    },
)
DESTROY_SCHEMA = extend_schema(
    description="delete an event",
    responses={
        "204": inline_serializer,
        "400": bad_request_serializer,
        "403": forbidden_request_serializer,
    },
)

ADD_PARTICIPANT_SCHEMA = extend_schema(
    description="add participant to an event",
    responses={
        "200": inline_serializer,
        "400": bad_request_serializer,
        "403": forbidden_request_serializer,
    },
)

REMOVE_PARTICIPANT_SCHEMA = extend_schema(
    description="remove participant from an event",
    responses={
        "204": inline_serializer,
        "400": bad_request_serializer,
        "403": forbidden_request_serializer,
    },
)
