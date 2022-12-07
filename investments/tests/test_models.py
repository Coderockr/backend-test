from django.test import TestCase
from model_bakery import baker
import ipdb

class InvestmentModelTest(TestCase):
    @classmethod
    def setUpTestData(cls):
        cls.investment_create = baker.make_recipe('investments.new_investment')
        
    def test_name_max_legth(self):

        max_length = self.investment_create._meta.get_field('amount').max_digits
        
        self.assertEqual(max_length, 10)

    def test_has_correct_fields(self):
        self.assertEqual(self.investment_create.amount, 100)
        self.assertEqual(self.investment_create.created_at, "2022-10-06")
        self.assertEqual(self.investment_create.gains, 0)
        self.assertEqual(self.investment_create.withdrawn_date, None)
        self.assertEqual(self.investment_create.expected_balance, 0)
        self.assertEqual(self.investment_create.initial_amount, None)
        self.assertTrue(self.investment_create.isActive)
        self.assertTrue(self.investment_create.owner_id)
    
    def test_amount_number_is_positive(self):
        self.assertTrue(self.investment_create.amount > 0)

    def test_episode_has_owner_id(self):
        self.assertTrue(self.investment_create.owner)
