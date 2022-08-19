from rest_framework.routers import SimpleRouter

from investment.api.views import InvestmentsViewSet, OwnerViewSet

router = SimpleRouter(trailing_slash=False)

router.register(r"investments", InvestmentsViewSet)
router.register(r"owners", OwnerViewSet)

