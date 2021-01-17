from django.contrib.auth.models import Permission

from events.core.models import CustomUser


def create_user_with_permission(permissions):
    """
    Create an user with the permissions passed in the parameter

    The user has following values by default:
        - email = somemail@gmail.com
        - username = someusername
        - city = somecity
        - state = somestate
        - password = somepassword

    Args:
        permissions (list): list of Strings with format <'app_name'.'codename_of_permission'>
    """
    user = CustomUser.objects.create_user(
        email="somemail@gmail.com", username="someusername", city="somecity", state="somestate"
    )
    user.set_password("somepassword")

    for permission in permissions:
        app_label, codename = permission.split(".")
        permission = Permission.objects.get(codename=codename, content_type__app_label=app_label)

        user.user_permissions.add(permission)

    return user


def add_user_to_participate_in_events(events: list, user):
    """
    Args:
        events (list): list of Event model objects
        user (CustomUser): CustomUser object model
    """
    for event in events:
        event.participants.add(user)


def add_friends(user, friends):
    for friend in friends:
        user.friends.add(friend)
