import json
from django.utils.timezone import now, timedelta
from model_bakery import baker
from rest_framework import status
from unittest.mock import patch

from ..serializers import InvestmentSerializer
from ..services import interest_svc


def test_create_investment(db, client, create_token, user_ains):
    data = {"amount": 100, "created_at": now().isoformat()}
    token = create_token(user_ains)

    response = client.post(
        "/investments/",
        data=json.dumps(data),
        content_type="application/json",
        HTTP_AUTHORIZATION=token,
    )
    response_data = response.json()

    assert response.status_code == status.HTTP_201_CREATED
    assert response_data["amount"] == data["amount"]


def test_create_investment_future_date(db, client, create_token, user_ains):
    created_at = now() + timedelta(days=5)
    data = {"amount": 100, "created_at": created_at.isoformat()}
    token = create_token(user_ains)

    response = client.post(
        "/investments/",
        data=json.dumps(data),
        content_type="application/json",
        HTTP_AUTHORIZATION=token,
    )

    assert response.status_code == status.HTTP_400_BAD_REQUEST


def test_create_investment_past_date(db, client, create_token, user_ains):
    created_at = now() - timedelta(days=5)
    data = {"amount": 100, "created_at": created_at.isoformat()}
    token = create_token(user_ains)

    response = client.post(
        "/investments/",
        data=json.dumps(data),
        content_type="application/json",
        HTTP_AUTHORIZATION=token,
    )
    response_data = response.json()

    assert response.status_code == status.HTTP_201_CREATED
    assert response_data["amount"] == data["amount"]


def test_withdrawn_investment(db, client, create_token, user_ains):
    baker.make(
        "investments.Investment",
        pk=1,
        amount=100,
        created_at=now() - timedelta(days=365),
        owner=user_ains,
    )
    withdrawn_at = now()
    data = {"withdrawn_at": withdrawn_at.isoformat()}
    token = create_token(user_ains)

    with patch("investments.views.send_withdrawn_alert_email_task.delay") as mock_task:
        response = client.post(
            "/investments/1/withdrawn/",
            data=json.dumps(data),
            content_type="application/json",
            HTTP_AUTHORIZATION=token,
        )
    response_data = response.json()

    expected_gain = interest_svc.gain_formula(100, 12)
    expected_gain_with_taxes = interest_svc._apply_tax(expected_gain - 100, 18.5)

    assert response.status_code == status.HTTP_202_ACCEPTED
    assert response_data["balance"] == expected_gain_with_taxes


def test_withdrawn_investment_before_created_at(db, client, create_token, user_ains):
    baker.make(
        "investments.Investment",
        pk=1,
        amount=100,
        created_at=now(),
        owner=user_ains,
    )
    withdrawn_at = now() - timedelta(days=60)  # 2 meses atrás
    data = {"withdrawn_at": withdrawn_at.isoformat()}
    token = create_token(user_ains)

    response = client.post(
        "/investments/1/withdrawn/",
        data=json.dumps(data),
        content_type="application/json",
        HTTP_AUTHORIZATION=token,
    )

    assert response.status_code == status.HTTP_400_BAD_REQUEST


def test_get_investment(db, client, create_token, user_ains):
    investment = baker.make(
        "investments.Investment",
        pk=1,
        amount=100,
        created_at=now(),
        owner=user_ains,
    )
    token = create_token(user_ains)
    serialized_investment = InvestmentSerializer(investment).data

    response = client.get("/investments/1/", HTTP_AUTHORIZATION=token)
    response_data = response.json()

    assert response.status_code == status.HTTP_200_OK
    assert response_data == serialized_investment


def test_get_investment_other_owner(db, client, create_token, user_ains):
    baker.make("investments.Investment", pk=1, amount=100, created_at=now())
    token = create_token(user_ains)

    response = client.get("/investments/1/", HTTP_AUTHORIZATION=token)

    assert response.status_code == status.HTTP_404_NOT_FOUND


def test_list_investments(db, client, create_token, user_ains):
    investments = baker.make(
        "investments.Investment",
        amount=100,
        created_at=now(),
        _quantity=10,
        owner=user_ains,
    )
    token = create_token(user_ains)

    response = client.get("/investments/", HTTP_AUTHORIZATION=token)
    response_data = response.json()

    assert response.status_code == status.HTTP_200_OK

    response_investments_id = [
        investment["id"] for investment in response_data["results"]
    ]

    for investment in investments:
        assert investment.id in response_investments_id


def test_list_investments(db, client, create_token, user_ains):
    baker.make(
        "investments.Investment",
        amount=100,
        created_at=now(),
        _quantity=15,
        owner=user_ains,
    )
    token = create_token(user_ains)

    response = client.get("/investments/", {"offset": 10}, HTTP_AUTHORIZATION=token)
    response_data = response.json()

    assert response.status_code == status.HTTP_200_OK
    assert response_data["count"] == 15
    assert response_data["previous"]
    assert not response_data["next"]
