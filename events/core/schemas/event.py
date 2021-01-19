from drf_spectacular.utils import extend_schema, inline_serializer
from rest_framework.serializers import CharField

LIST_SCHEMA = extend_schema(description="list all events")
RETRIEVE_SCHEMA = extend_schema(description="retrieve an event")
UPDATE_SCHEMA = extend_schema(description="update an event")
PARTIAL_UPDATE_SCHEMA = extend_schema(description="update an event")
CREATE_SCHEMA = extend_schema(description="create an event")
DESTROY_SCHEMA = extend_schema(description="delete an event")

ADD_PARTICIPANT_SCHEMA = extend_schema(
    description="add participant to an event",
    responses={
        "204": inline_serializer,
        "400": inline_serializer(
            name="bad_request",
            fields={
                "detail": CharField(),
            },
        ),
    },
)

REMOVE_PARTICIPANT_SCHEMA = extend_schema(
    description="remove participant from an event",
    responses={
        "204": inline_serializer,
        "400": inline_serializer(
            name="bad_request",
            fields={
                "detail": CharField(),
            },
        ),
    },
)
