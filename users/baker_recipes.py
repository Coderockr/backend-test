from model_bakery.recipe import Recipe
from users.models import User

new_user = Recipe(
    User,
    email= "kenzinho@gmail.com",
	username= "kenzinho"
)