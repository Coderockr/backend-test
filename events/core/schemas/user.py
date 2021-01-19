from drf_spectacular.utils import extend_schema, inline_serializer

from events.core.schemas.reusable import bad_request_serializer, forbidden_request_serializer
from events.core.serializers import ListEventSerializer, ListInvitationSerializer, ListUserSerializer

DELETE_USER = extend_schema(
    description="remove an friendship",
    responses={
        "200": inline_serializer,
        "400": bad_request_serializer,
        "403": forbidden_request_serializer,
        "404": bad_request_serializer,
    },
)

ACCOUNT_ACTIVATION_USER = extend_schema(
    description="active an user account",
    responses={
        "200": inline_serializer,
        "400": bad_request_serializer,
    },
)

EVENT_INVITATIONS_USER = extend_schema(
    description="return all invitations of type EV (Event)",
    responses={
        "200": ListInvitationSerializer,
        "404": forbidden_request_serializer,
    },
)

FRIENDS_USER = extend_schema(
    description="return all friends",
    responses={
        "200": ListUserSerializer,
        "404": forbidden_request_serializer,
    },
)

FRIENDSHIP_INVITATIONS_USER = extend_schema(
    description="return all invitations of type FS (Friendship)",
    responses={
        "200": ListInvitationSerializer,
        "404": forbidden_request_serializer,
    },
)

MY_EVENTS_USER = extend_schema(
    description="return all events that user is owner or participate",
    responses={
        "200": ListEventSerializer,
        "404": forbidden_request_serializer,
    },
)

REJECTED_EVENTS_USER = extend_schema(
    description="return all events that user rejected",
    responses={
        "200": ListInvitationSerializer,
        "404": forbidden_request_serializer,
    },
)
