from rest_framework import routers
from persons.views import PersonViewSet
from investments.views import InvestmentViewSet, WithdrawnViewSet


router = routers.DefaultRouter()
router.register('persons', PersonViewSet)
router.register('investments', InvestmentViewSet)
router.register('investments', WithdrawnViewSet)