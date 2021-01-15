from rest_framework.permissions import SAFE_METHODS, BasePermission


class ReadOnly(BasePermission):
    def has_permission(self, request, view):
        return request.method in SAFE_METHODS


class IsEventOwner(BasePermission):
    """
    Block whether the user is non-owner event
    """

    def has_object_permission(self, request, view, obj):
        return request.user == obj.owner
