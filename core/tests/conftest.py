import pytest
from rest_framework.authtoken.models import Token

@pytest.fixture
def create_token_for_user():
  def wrapper(user):
    return Token.objects.create(user=user)

  return wrapper
