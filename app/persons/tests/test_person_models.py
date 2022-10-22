from persons.tests.test_person_base import PersonTestBase

class PersonModelTest(PersonTestBase):
    def setUp(self):
       self.person = self.create_person(email='teste@example.com', name='Teste')
       return super().setUp()
   
    def test_the_test(self):
        person = self.person