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


# should use template html to create messages
def send_email_to_register(destination_email):
    email = EmailMessage()
    email.subject = "Join our network!"
    email.body = """
    Hi, bro!

    Join us and participate our events !!!

    Hugs,
    """
    email.to = [destination_email]
    email.send()


def __send_invitation_creation_notification_by_email(sender, instance):
    destination_email = instance.invitation_to.email

    if CustomUser.objects.is_unregistered(destination_email):  # pragma: no cover -> no complexity
        send_email_to_register(destination_email)
    else:
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
