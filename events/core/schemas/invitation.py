from drf_spectacular.utils import extend_schema

from events.core.schemas.reusable import bad_request_serializer, forbidden_request_serializer
from events.core.serializers import CreateInvitationSerializer

UPDATE_SCHEMA = extend_schema(
    description="update an invitation and send email when updated",
    responses={
        "201": CreateInvitationSerializer,
        "400": bad_request_serializer,
        "403": forbidden_request_serializer,
        "404": bad_request_serializer,
    },
)

PARTIAL_UPDATE_SCHEMA = extend_schema(
    description="update an invitation and send email when updated",
    responses={
        "201": CreateInvitationSerializer,
        "400": bad_request_serializer,
        "403": forbidden_request_serializer,
        "404": bad_request_serializer,
    },
)

DESTROY_SCHEMA = extend_schema(
    description="delete an invitation",
    responses={
        "201": CreateInvitationSerializer,
        "400": bad_request_serializer,
        "403": forbidden_request_serializer,
        "404": bad_request_serializer,
    },
)

CREATE_SCHEMA = extend_schema(
    description="create an invitation and send email when created",
    responses={
        "201": CreateInvitationSerializer,
        "400": bad_request_serializer,
        "403": forbidden_request_serializer,
    },
)
