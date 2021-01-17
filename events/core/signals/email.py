from django.core.mail.message import EmailMessage
from django.db.models.signals import post_save
from django.dispatch.dispatcher import receiver

from events.core.models import Invitation
from events.core.models.user import CustomUser


@receiver(signal=post_save, sender=Invitation, dispatch_uid="send_invitation_email")
def send_invitation_email(sender, **kwargs):
    if kwargs.get("created"):
        instance = kwargs.get("instance")

        __send_generic_email(sender, instance)


def is_unregistered_destination(destination):
    try:
        CustomUser.objects.get(email=destination)
    except CustomUser.DoesNotExist:
        return True


def send_email_to_register(destination):
    """
    Send an invitation email to register and return True if was succeed
    """
    if is_unregistered_destination(destination):
        email = EmailMessage()
        email.subject = "Join our network!"
        email.body = """
        Hi, bro!

        Join us and participate in our events !!!

        Hugs,
        """
        email.to = ["luiscvlh11@gmail.com"]
        email.send()

        print("+++++++++++++++++++")
        print("Register Email sent")
        print("+++++++++++++++++++")

        return True


def __send_generic_email(sender, instance):
    choices = dict(sender.type.field.choices)
    choice_label = choices.get(instance.type.upper())

    email = EmailMessage()
    email.subject = f"You have a new {choice_label} Invitation"
    email.body = f"""
    Hi {instance.invitation_to.username},

    {instance.invitation_from.username} sent an email to you.

    Hugs,
    """
    email.to = [instance.invitation_to.email]
    email.send()

    print("++++++++++++++++++")
    print("Generic Email sent")
    print("++++++++++++++++++")
