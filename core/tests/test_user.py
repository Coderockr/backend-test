from http import HTTPStatus
import json
from model_bakery import baker
from ..models import User


def test_create(db, client):
    data = {"email": "jorge@gmail.com", "password": "jorge"}

    response = client.post(
        "/user/",
        data=json.dumps(data),
        content_type="application/json",
    )
    response_data = response.json()

    assert response.status_code == HTTPStatus.CREATED
    assert response_data["email"] == data["email"]


def test_create_duplicate(db, client):
    data = {"email": "robson@gmail.com", "password": "jorge"}

    User.objects.create_user(**data, username=data["email"])

    response = client.post(
        "/user/",
        data=json.dumps(data),
        content_type="application/json",
    )

    print(User.objects.all())

    assert response.status_code == HTTPStatus.BAD_REQUEST


def test_update(db, client, create_token_for_user):
    user_jorge = baker.make("core.User", id=1, email="jorge@jorge.com")
    token = "Token " + create_token_for_user(user_jorge).key
    data = {"email": "jorge@gmail.com"}

    response = client.patch(
        "/user/1/",
        data=json.dumps(data),
        HTTP_AUTHORIZATION=token,
        content_type="application/json",
    )
    response_data = response.json()

    assert response.status_code == HTTPStatus.OK
    assert response_data["email"] == data["email"]


def test_update_other_user(db, client, create_token_for_user):
    user_jorge = baker.make("core.User", id=1, email="jorge@gmail.com")
    baker.make("core.User", id=2, email="marcio@marcio.com")
    token_jorge = "Token " + create_token_for_user(user_jorge).key
    data = {"email": "marcio@gmail.com"}

    response = client.patch(
        "/user/2/",
        data=json.dumps(data),
        HTTP_AUTHORIZATION=token_jorge,
        content_type="application/json",
    )

    assert response.status_code == HTTPStatus.FORBIDDEN


def test_delete(db, client, create_token_for_user):
    user_jorge = baker.make("core.User", id=1, email="jorge@jorge.com")
    token = "Token " + create_token_for_user(user_jorge).key

    response = client.delete("/user/1/", HTTP_AUTHORIZATION=token)

    assert response.status_code == HTTPStatus.NO_CONTENT


def test_delete_other_user(db, client, create_token_for_user):
    user_jorge = baker.make("core.User", id=1, email="jorge@jorge.com")
    baker.make("core.User", id=2, email="marcio@marcio.com")

    token = "Token " + create_token_for_user(user_jorge).key

    response = client.delete("/user/2/", HTTP_AUTHORIZATION=token)

    assert response.status_code == HTTPStatus.FORBIDDEN
