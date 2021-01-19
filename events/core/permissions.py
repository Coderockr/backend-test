from rest_framework.permissions import SAFE_METHODS, BasePermission


class ReadOnly(BasePermission):
    def has_permission(self, request, view):
        return request.method in SAFE_METHODS


class IsEventOwner(BasePermission):
    def has_object_permission(self, request, view, obj):
        return request.user == obj.owner


class CanChangeOrDeleteEvent(BasePermission):
    """
    Block whether the user is not event owner or has not django permission
    """

    def has_object_permission(self, request, view, obj):
        return request.user == obj.owner or request.user.has_perms(("core.change_event", "core.delete_event"))


class CanDeleteInvitation(BasePermission):
    """
    Block whether user not received or sent invitation or has not django permission
    """

    def has_object_permission(self, request, view, obj):
        return (
            # the user that received invitation
            request.user == obj.invitation_to
            # the user that sent invitation
            or request.user == obj.invitation_from
            # the user that has django permission
            or request.user.has_perm("core.delete_invitation")
        )


class CanChangeInvitation(BasePermission):
    """
    Block whether user not received invitation or has not django permission
    """

    def has_object_permission(self, request, view, obj):
        return request.user == obj.invitation_to or request.user.has_perm("core.change_invitation")
