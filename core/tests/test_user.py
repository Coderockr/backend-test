import json
from model_bakery import baker
from rest_framework import status
from ..models import User


def test_create(db, client):
    data = {"email": "jorge@gmail.com", "password": "jorge"}

    response = client.post(
        "/users",
        data=json.dumps(data),
        content_type="application/json",
    )
    response_data = response.json()

    assert response.status_code == status.HTTP_201_CREATED
    assert response_data["email"] == data["email"]


def test_create_duplicate(db, client):
    data = {"email": "robson@gmail.com", "password": "jorge"}

    User.objects.create_user(**data, username=data["email"])

    response = client.post(
        "/users",
        data=json.dumps(data),
        content_type="application/json",
    )

    assert response.status_code == status.HTTP_400_BAD_REQUEST


def test_update(db, client, create_token):
    user_jorge = baker.make("core.User", pk=1, email="jorge@jorge.com")
    token = create_token(user_jorge)
    data = {"email": "jorge@gmail.com"}

    response = client.patch(
        "/users/1",
        data=json.dumps(data),
        HTTP_AUTHORIZATION=token,
        content_type="application/json",
    )
    response_data = response.json()

    assert response.status_code == status.HTTP_200_OK
    assert response_data["email"] == data["email"]


def test_update_other_user(db, client, create_token):
    user_jorge = baker.make("core.User", pk=1, email="jorge@gmail.com")
    baker.make("core.User", pk=2, email="marcio@marcio.com")
    token_jorge = create_token(user_jorge)
    data = {"email": "marcio@gmail.com"}

    response = client.patch(
        "/users/2",
        data=json.dumps(data),
        HTTP_AUTHORIZATION=token_jorge,
        content_type="application/json",
    )

    assert response.status_code == status.HTTP_404_NOT_FOUND


def test_delete(db, client, create_token):
    user_jorge = baker.make("core.User", pk=1, email="jorge@jorge.com")
    token = create_token(user_jorge)

    response = client.delete("/users/1", HTTP_AUTHORIZATION=token)

    assert response.status_code == status.HTTP_204_NO_CONTENT


def test_delete_other_user(db, client, create_token):
    user_jorge = baker.make("core.User", pk=1, email="jorge@jorge.com")
    baker.make("core.User", pk=2, email="marcio@marcio.com")

    token = create_token(user_jorge)

    response = client.delete("/users/2", HTTP_AUTHORIZATION=token)

    assert response.status_code == status.HTTP_404_NOT_FOUND
