from rest_framework.permissions import SAFE_METHODS, BasePermission


class ReadOnly(BasePermission):
    def has_permission(self, request, view):
        return request.method in SAFE_METHODS


class CanChangeOrDestroyEvent(BasePermission):
    """
    Block whether the user is not event owner or has not django permission
    """

    def has_object_permission(self, request, view, obj):
        return request.user == obj.owner or request.user.has_perms(("core.change_event", "core.delete_event"))


class CanChangeOrDestroyInvitation(BasePermission):
    """
    Block whether the user is not invitation author or has not django permission
    """

    def has_object_permission(self, request, view, obj):
        return request.user == obj.invitation_from or request.user.has_perms(
            ("core.change_invitation", "core.delete_invitation")
        )
