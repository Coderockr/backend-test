from django.urls import path
from drf_spectacular.views import SpectacularAPIView, SpectacularSwaggerView, SpectacularRedocView

from drf_spectacular.views import SpectacularAPIView, SpectacularRedocView

from users.views import CreateUserView, ListUpdateDeleteDetailUserView

urlpatterns=[
    path('accounts/', CreateUserView.as_view()),
    path('accounts/<str:id>/', ListUpdateDeleteDetailUserView.as_view()),
    path('schema/', SpectacularAPIView.as_view(), name='schema'),
    path('docs/', SpectacularSwaggerView.as_view(url_name='schema'), name='swagger-ui'),
    path('schema/', SpectacularAPIView.as_view(), name='schema'),
]
