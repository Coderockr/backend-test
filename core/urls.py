from django.urls import path, include
from rest_framework.routers import SimpleRouter
from rest_framework.authtoken.views import obtain_auth_token
from .views import UserViewSet


router = SimpleRouter(trailing_slash=False)
router.register("users", UserViewSet)

urlpatterns = [
    path(r"login/", obtain_auth_token),
    path("", include(router.urls)),
]
