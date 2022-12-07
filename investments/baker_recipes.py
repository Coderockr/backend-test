from model_bakery.recipe import Recipe
from investments.models import Investment

new_investment = Recipe(
    Investment,
    amount= 100,
	created_at= "2022-10-06"
)