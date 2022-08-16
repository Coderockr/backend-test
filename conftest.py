import pytest
from model_bakery import baker
from rest_framework.authtoken.models import Token
from core.models import User

@pytest.fixture
def create_token():
  def wrapper(user):
    token = Token.objects.create(user=user).key
    return f"Token {token}"

  return wrapper


@pytest.fixture
def user_jorge():
  return User.objects.create_user(
    username="ains",
    email="jorge@outlook.com",
    password="jorge123"
  )


@pytest.fixture
def user_ains():
  return User.objects.create_user(
    username="ains",
    email="ains@nazarick.com",
    password="AinsOoalGownRulesItAll"
  )
