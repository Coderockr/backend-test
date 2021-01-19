from drf_spectacular.utils import extend_schema, inline_serializer

from events.core.serializers import CreateInvitationSerializer

UPDATE_SCHEMA = extend_schema(description="update an invitation and send email when updated")
PARTIAL_UPDATE_SCHEMA = extend_schema(description="update an invitation and send email when updated")
DESTROY_SCHEMA = extend_schema(description="delete an invitation")

CREATE_SCHEMA = extend_schema(
    description="create an invitation and send email when created",
    responses={
        "200": CreateInvitationSerializer,
        "204": inline_serializer,
    },
)
