from rest_framework.test import APITestCase

from model_bakery import baker
import ipdb

from investments.models import Investment
from investments.serializers import InvestmentDetailSerializer

class InvestmentTestViews(APITestCase):
    
    @classmethod
    def setUpTestData(cls):

        cls.user_create = baker.make_recipe('users.new_user')

        cls.create_investment_uri = f'/api/investments/{cls.user_create.id}/'

        cls.get_investments_uri = '/api/investments/'

        cls.valid_investment = {
            "amount": 1000,
            "created_at": "2022-10-06"
        }

        cls.invalid_investment_1 = {
            "amount": -100,
            "created_at": "2022-10-06"
        }

        cls.invalid_investment_2 = {
            "amount": 100,
            "created_at": "06/07/2022"
        }

        cls.invalid_investment_3 = {
            "amount": 100,
            "created_at": "2024-07-07"
        }

        cls.valid_investment_1 = {
            "amount": 1000,
            "created_at": "2022-12-06"
        }

        cls.response_post_create_investment = {
            "amount": "1000.00",
            "created_at": "2022-12-06",
            "gains": "0.00",
            "owner": cls.user_create.id,
            "expected_balance": "0.00",
            "initial_amount": "1000.00"
        }

        cls.valid_withdrawn_date = {
            "withdrawn_date": "2022-12-06"
        }

        cls.invalid_withdrawn_date = {
            "withdrawn_date": "2024-12-06"
        }

        cls.valid_investment = Investment.objects.create(**cls.valid_investment, owner_id=cls.user_create.id)

        cls.get_investment_uri = f'/api/investment/{cls.valid_investment.id}/'

        cls.withdrawn_investment_uri = f'/api/investment/{cls.valid_investment.id}/withdrawn/'

        cls.delete_investment_uri = f'/api/investment/{cls.valid_investment.id}/'
     
    def test_can_register_investment(self):
        response_post = self.client.post(self.create_investment_uri, data=self.valid_investment_1, format="json")
        del response_post.data['id']
        self.assertEqual(self.response_post_create_investment, response_post.data)
        self.assertEqual(response_post.status_code, 201)

    def test_cannot_register_negative_investment(self):
        response_post = self.client.post(self.create_investment_uri, self.invalid_investment_1, format="json")
        self.assertEqual(response_post.status_code, 400)
        for key, value in response_post.data.items():
            self.assertEqual(value[0][:], f"{self.invalid_investment_1['amount']}.00 is a invalid number, please only positive numbers!")

    def test_cannot_register_investment_invalid_formate_date_1(self):
        response_post = self.client.post(self.create_investment_uri, self.invalid_investment_2, format="json")
        self.assertEqual(response_post.status_code, 400)
        for key, value in response_post.data.items():
            self.assertEqual(value[0][:], "Date has wrong format. Use one of these formats instead: YYYY-MM-DD.")

    def test_cannot_register_investment_invalid_formate_date_2(self):

        response_post = self.client.post(self.create_investment_uri, self.invalid_investment_3, format="json")
        self.assertEqual(response_post.status_code, 400)
        
        self.assertEqual(response_post.data, {"detail": "Invalid Date!"})
    
    def test_cannot_register_empty_investment(self):
        response_post = self.client.post(self.create_investment_uri, {}, format="json")
        self.assertEqual(response_post.status_code, 400)
        for key, value in response_post.data.items():
            self.assertEqual(value[0][:], "This field is required.")

    def test_can_list_investments(self):
        response = self.client.get(self.get_investments_uri)
        self.assertEqual(200, response.status_code)
        self.assertEqual(response.data['count'], 1)
    
    def test_can_list_one_investment(self):
        response = self.client.get(self.get_investment_uri)
        self.assertEqual(200, response.status_code)

    def test_can_withdrawn_investment(self):
        self.client.get(self.get_investment_uri)
        response_withdrawn = self.client.post(self.withdrawn_investment_uri, self.valid_withdrawn_date, format="json")
        self.assertEqual(response_withdrawn.status_code, 201)
        self.assertEqual(response_withdrawn.data['withdrawn_date'], self.valid_withdrawn_date['withdrawn_date'])
        
    def test_can_safe_delete_investment(self):
        response_delete = self.client.delete(self.delete_investment_uri)
        self.assertEqual(response_delete.status_code, 204)

