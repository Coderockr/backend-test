from rest_framework import routers
from persons.views import UserViewSet
from investments.views import InvestmentViewSet, WithdrawnViewSet


router = routers.DefaultRouter()
router.register('persons', UserViewSet)
router.register('investments', InvestmentViewSet)
router.register('investments', WithdrawnViewSet)