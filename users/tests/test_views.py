from rest_framework.test import APITestCase

import ipdb

from users.serializers import  UserDetailSerializer, UserSerializer
from users.models import User


class UserTestViews(APITestCase):
    
    @classmethod
    def setUpTestData(cls):

        cls.create_user_uri = "/api/accounts/"

        cls.valid_user = {
            "username": "kenzinho",
            "email": "kenzinho@kenzinho.com"
        }

        cls.valid_user_1 = {
            "username": "kenzinho1",
            "email": "kenzinho@kenzinho1.com"
        }

        cls.invalid_user = {
            "username": "",
            "email": ""
        }
        cls.owner_1 = User.objects.create_user(**cls.valid_user_1)
 
        
    
    def test_can_register_user(self):
        response_post = self.client.post(self.create_user_uri, self.valid_user, format="json")
        self.assertEqual(UserSerializer(instance=response_post.data).data, response_post.data)
        self.assertEqual(response_post.status_code, 201)

    def test_cannot_register_user(self):
        response_post = self.client.post(self.create_user_uri, {}, format="json")
        self.assertEqual(response_post.status_code, 400)
        for key, value in response_post.data.items():
            self.assertEqual(value[0][:], "This field is required.")
        
    def test_can_edit_user(self):
        response_patch = self.client.patch(f"/api/accounts/{self.owner_1.id}/", {"username": "KenzinhoUpdated"}, format="json")
        self.assertEqual(UserDetailSerializer(instance=response_patch.data).data, response_patch.data)
        self.assertEqual(response_patch.status_code, 200)
    
    def test_can_safe_delete_user(self):
        response_delete = self.client.delete(f"/api/accounts/{self.owner_1.id}/")
        self.assertEqual(response_delete.status_code, 204)
