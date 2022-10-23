from django.core.mail import send_mail
from django.db import models
from django.db.models.signals import post_save
from django.dispatch import receiver
import pandas as pd
from datetime import datetime

from persons.models import Person


class Investment(models.Model):
    owner = models.ForeignKey(Person, related_name='investments', blank=True, null=True,on_delete=models.CASCADE)
    creation_date = models.DateField()
    initial_amount = models.FloatField()
    withdrawn_date = models.DateField(blank=True, null=True)
    active = models.BooleanField(default=True)
    
    def __str__(self):
        return f'{self.owner}'

    class Meta:
        verbose_name = 'Investment'
        

@receiver(post_save, sender=Investment)
def send_mail_on_create(sender, instance=None, created=False, **kwargs):
    if created:
        initial_amount = instance.initial_amount
        df = pd.DataFrame(data={'date':[datetime.strptime(str(instance.creation_date), '%Y-%m-%d')]})
        day = df.date.dt.day[0]
        name = instance.owner.name
        email = send_mail(
            'You have a new investment',
            f'Hi {name}, \n\nYou have a new investment in your name. The initial amount is ${initial_amount}, this value increases by 0.52% every month on day {day}. \nThank you for the trust, have a great day',
            'investmentscoderockr@outlook.com',
            [instance.owner.email],
        )
