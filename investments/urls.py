from django.urls import path, include
from rest_framework.routers import SimpleRouter

from .views import InvestmentViewSet


router = SimpleRouter()
router.register("", InvestmentViewSet)

urlpatterns = [
    path("", include(router.urls)),
]
