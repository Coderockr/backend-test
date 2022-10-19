import requests
from rest_framework import test

BASE_URL = 'http://127.0.0.1:8000/api/V1/'

class TestUsers(test.APITestCase):
    BASE_URI = 'users/'
    
    def test_get_users(self):
        users = requests.get(BASE_URL + self.BASE_URI)

        assert users.status_code == 200
    
    def test_get_user(self):
        user = requests.get(BASE_URL + self.BASE_URI + '1')
        
        assert user.status_code == 200
        
    def test_post_user(self):
        data = {"email": "teste2@gmail.com", "name": "teste"}
        user = self.client.post(url=BASE_URL + self.BASE_URI, data=data)
        
        
        
        assert user.status_code == 201
