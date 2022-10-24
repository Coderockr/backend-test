from urllib import response
from investments.tests.test_investments_base import InvestmentsTestBase
from datetime import date, timedelta, datetime

class InvestmentsAPIv1Test(InvestmentsTestBase):
    BASE_URL = 'http://127.0.0.1:8000/api/V1/investments/'
    
    def test_person_post_returns_status_code_201(self):
        response = self.client.post(
            'http://127.0.0.1:8000/api/V1/persons/',
            data={
                'email': 'example@example.com',
                'name': 'Teste'
            })
        return response.data.get('id')
    
    def test_investment_list_returns_status_code_200(self):
        response = self.client.get(self.BASE_URL)
        self.assertEqual(response.status_code, 200)
    
    def test_investment_list_has_pagination(self):
        wanted_investments = 100
        self.create_investment_in_batch(qtd=wanted_investments)
        
        response = self.client.get(self.BASE_URL)
        
        assert response.data.get('next') is not None
        
        return {"response": response.data, "wanted_investments": wanted_investments}
        

    def test_investments_post_returns_status_code_201(self):
        person = self.test_person_post_returns_status_code_201()
        
        response = self.client.post(
            self.BASE_URL,
            data={
                'owner': person,
                'creation_date': '2019-10-22',
                'initial_amount': 10000
            }
        )
        
        self.assertEqual(
            response.status_code,
            201
        )
        
        return response.data

    def test_investment_cannot_be_created_without_an_owner(self):
        response = self.client.post(self.BASE_URL)
        
        self.assertEqual(
            response.status_code,
            400
        )
    
    def test_investments_creation_date_cannot_be_in_the_future(self):
        person = self.test_person_post_returns_status_code_201()
        today_date = date.today()
        future_date = today_date + timedelta(1)
        
        response = self.client.post(
            self.BASE_URL,
            data={
                'owner': person,
                'creation_date': future_date,
                'initial_amount': 10000
            }
        )
        
        self.assertEqual(
            response.status_code,
            400
        )
        
    def test_investment_initial_amount_cannot_be_negative(self):
        person = self.test_person_post_returns_status_code_201()
        response = self.client.post(
            self.BASE_URL,
            data={
                'owner': person,
                'creation_date': '2019-10-22',
                'initial_amount': -2
            }
        )
        self.assertEqual(
            response.status_code,
            400
        )
    
    def test_investment_withdrawal_returns_status_code_200(self):
        investment = self.test_investments_post_returns_status_code_201()
        investment_id = investment['id']
        
        response = self.client.put(f'{self.BASE_URL}{investment_id}/withdrawn/', data={'withdrawn_date': '2022-10-22'})
        
        self.assertEqual(
            response.status_code,
            200
        )
        
        return investment

    def test_investment_cannot_have_more_than_one_withdrawal(self):
        investment = self.test_investment_withdrawal_returns_status_code_200()
        investment_id = investment['id']
        
        response = self.client.put(f'{self.BASE_URL}{investment_id}/withdrawn/', data={'withdrawn_date': '2022-10-22'})
        
        self.assertEqual(
            response.status_code,
            400
        )
    
    def test_investments_withdrawal_date_cannot_be_before_it_creation_date(self):
        investment = self.test_investments_post_returns_status_code_201()
        
        investment_id = investment['id']
        creation_date = datetime.strptime(str(investment['creation_date']), '%Y-%m-%d')
        withdrawn_date = (creation_date - timedelta(1)).date()
        
        response = self.client.put(f'{self.BASE_URL}{investment_id}/withdrawn/', data={'withdrawn_date': withdrawn_date})
        
        self.assertEqual(
            response.status_code,
            400
        )
        
    def test_investments_withdrawal_date_cannot_be_in_the_future(self):
        investment = self.test_investments_post_returns_status_code_201()
        
        investment_id = investment['id']
        now = datetime.today()
        withdrawn_date = (now + timedelta(1)).date()
        
        response = self.client.put(f'{self.BASE_URL}{investment_id}/withdrawn/', data={'withdrawn_date': withdrawn_date})
        self.assertEqual(
            response.status_code,
            400
        )
        
    def test_person_investments_list_return_all_investments(self):
        investments = self.test_investment_list_has_pagination()
        person_id = investments.get('response').get('results')[0].get('owner')
        wanted_investments = investments.get('wanted_investments')
        
        response = self.client.get(f'http://127.0.0.1:8000/api/V1/persons/{person_id}/investments/')
        
        self.assertEqual(
            response.data.get('count'),
            wanted_investments
        )
        
       
        
        