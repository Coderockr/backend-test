from rest_framework.routers import SimpleRouter

from events.core.views import EventViewSet, InvitationViewSet, UserViewSet

router = SimpleRouter()
router.register("user", UserViewSet, basename="user")
router.register("invitation", InvitationViewSet, basename="invitation")

# no make sense but this must be register at the end
router.register("", EventViewSet, basename="event")
