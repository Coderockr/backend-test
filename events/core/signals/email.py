from django.core.mail.message import EmailMessage
from django.db.models.signals import post_save
from django.dispatch.dispatcher import receiver

from events.core.models import CustomUser, Invitation


@receiver(signal=post_save, sender=Invitation, dispatch_uid="send_invitation_email")
def send_invitation_email(sender, **kwargs):
    instance = kwargs.get("instance")

    if kwargs.get("created"):
        __send_invitation_creation_notification_by_email(sender, instance)
    else:
        __send_invitation_update_notification_by_email(sender, instance)


def is_unregistered_destination(destination_email):
    try:
        CustomUser.objects.get(email=destination_email)
    except CustomUser.DoesNotExist:
        return True


# should use template html to create messages
def send_email_to_register(destination_emails):
    email = EmailMessage()
    email.subject = "Join our network!"
    email.body = """
    Hi, bro!

    Join us and participate in our events !!!

    Hugs,
    """
    email.to = [destination_emails]
    email.send()


def __send_invitation_creation_notification_by_email(sender, instance):
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


def __send_invitation_update_notification_by_email(sender, instance):
    choices = dict(sender.status.field.choices)
    choice_label = choices.get(instance.status.upper())

    email = EmailMessage()
    email.subject = "Invitation status updated"
    email.body = f"""
    Hi {instance.invitation_from.username},

    The invitation sent to {instance.invitation_to.username} was {choice_label}.

    Hugs,
    """
    email.to = [instance.invitation_from.email]
    email.send()
