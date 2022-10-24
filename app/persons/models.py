from django.db import models

class Person(models.Model):
    name = models.CharField(max_length=256)
    email = models.EmailField('Email Adress', unique=True)

    def __str__(self):
        return f'{self.name}'

    class Meta:
        verbose_name = 'User'
