from django.core.mail import send_mail
from celery import shared_task


@shared_task()
def send_withdrawn_alert_email_task(email_address, amount):
  send_mail(
    "You have made an withdrawn",
    f"An withdrawn of {amount} just occurred on your account!",
    "fake@example.com",
    [email_address]
  )