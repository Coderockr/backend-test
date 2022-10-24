from random import randrange
from rest_framework.test import APITestCase
from investments.models import Investment
from persons.models import Person

class InvestmentsMixin:
    def create_person(self, email, name):
        return Person.objects.create(email=email, name=name)
    
    def create_investment(self, owner, creation_date, initial_amount):
        return Investment.objects.create(owner=owner, creation_date=creation_date, initial_amount=initial_amount)
    
    def create_investment_in_batch(self, qtd=0):
        investments = []
        person = self.create_person(email='teste@example.com', name='Teste')
        for i in range(qtd):
            kwargs = {
                'owner': person,
                'creation_date': '2019-10-22',
                'initial_amount': randrange(10000, 50000)
            }
            investment = self.create_investment(**kwargs)
            investments.append(investment)
        return investments

    
class InvestmentsTestBase(APITestCase, InvestmentsMixin):
    def setUp(self) -> None:
        return super().setUp()
