from django.contrib import admin
from .models import Investments, Owner

@admin.register(Investments)
class InvestmentsAdmin(admin.ModelAdmin):
    list_display = ("owner", "creation_date", "amount")

@admin.register(Owner)
class OwnerAdmin(admin.ModelAdmin):
    list_display = ("name",)

