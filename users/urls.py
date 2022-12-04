from django.urls import path

from drf_spectacular.views import SpectacularAPIView, SpectacularRedocView

from users.views import CreateUserView, ListUpdateDeleteDetailUserView

urlpatterns=[
    path('accounts/', CreateUserView.as_view()),
    path('accounts/<str:id>/', ListUpdateDeleteDetailUserView.as_view()),
    path('schema/', SpectacularAPIView.as_view(), name='schema'),
    path('docs/', SpectacularRedocView.as_view(url_name='schema'), name='redoc')
]
