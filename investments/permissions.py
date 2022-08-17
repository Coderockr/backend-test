from rest_framework.permissions import BasePermission


class IsOwnInvestment(BasePermission):
    def has_object_permission(self, request, view, obj):
        return obj.owner.id == request.user.id
