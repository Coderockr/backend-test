from django.urls import path

from investments.views import CreateInvestmentView, ListInvestmentView, RetrieveUpdateDestroyInvestmentDetailView


urlpatterns=[
    path('investments/', ListInvestmentView.as_view()),
    path('investments/<str:owner_id>/', CreateInvestmentView.as_view()),
    path('investment/<str:investment_id>/', RetrieveUpdateDestroyInvestmentDetailView.as_view())
]
