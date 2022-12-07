from django.urls import path

from investments.views import CreateInvestmentView, ListInvestmentView, ListOneUserInvestmentsView, RetrieveUpdateDestroyInvestmentDetailView, WithdrawnInvestmentView


urlpatterns=[
    path('investments/', ListInvestmentView.as_view()),
    path('investments/<str:owner_id>/', CreateInvestmentView.as_view()),
    path('investments/<str:owner_id>/management/', ListOneUserInvestmentsView.as_view()),
    path('investment/<str:investment_id>/', RetrieveUpdateDestroyInvestmentDetailView.as_view()),
    path('investment/<str:investment_id>/withdrawn/', WithdrawnInvestmentView.as_view()),

]
