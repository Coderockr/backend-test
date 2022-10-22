from rest_framework.test import APITestCase
from persons.models import Person

class PersonMixin:
    def create_person(self, email, name):
        return Person.objects.create(email=email, name=name)
    

class PersonTestBase(APITestCase, PersonMixin):
    def setUp(self) -> None:
        return super().setUp()
