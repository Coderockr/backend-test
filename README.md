# Back End Test Project <img src="https://coderockr.com/assets/images/coderockr.svg" align="right" height="50px" />

## Build instructions.

1. Clone this repository locally.
2. CD into the folder.
3. Run `docker-compose build`.
4. Run `docker-compose up -d`.
5. Access http://localhost:8000/swagger to view the OpenAPI Specification.
6. Create a user in the users_create route.
7. Create and copy the token in the login_create route.
8. Click in the "Authorize 🔒" button.
9. Enter "Token " + your token and then in "Authorize" button.
10. Now you're ready to interact with the API.

### Extra instructions.

* Run `docker-compose exec app pytest` to run the integration tests.
* Run `docker-compose exec app black .` to run the black linter.

## Dependencies and theirs "why's".

1. Django - Python "batteries-included" web-framework, was one of the requirements for the role.
2. psycopg2 - Python adapter for PostgreSQL (The best OS database option).
3. django-rest-framework - Django Framework that makes the development of Rest API easier, well structured and standardized.
4. Markdown - It gives DRF a nice interface, a great alternative to the OpenAPI specs.
5. drf-yasg - Is the responsible for the Swagger route, it automatically generates the specs based on the project routes.

### Development dependencies.

1. pytest - Python testing lib.
2. pytest-django - Pytest adapter is a plugin for Django.
3. model-bakery - Its a utility function to mock database models.
4. debugpy - Service that allows debugging python remotely.
5. black - Python linter, to enforce coding style, it has a "zero configuration" police, I have chosen it just for being easier and faster to setup, flake8 is better.

## Requirements.

1. __Creation__ of an investment with an owner, a creation date and an amount.
    1. [x] The creation date of an investment can be today or a date in the past.
    2. [x] An investment should not be or become negative.
2. __View__ of an investment with its initial amount and expected balance.
    1. [x] Expected balance should be the sum of the invested amount and the [gains][].
    2. [x] If an investment was already withdrawn then the balance must reflect the gains of that investment
3. __Withdrawal__ of a investment.
    1. [x] The withdraw will always be the sum of the initial amount and its gains,
       partial withdrawn is not supported.
    2. [x] Withdrawals can happen in the past or today, but can't happen before the investment creation or the future.
    3. [x] [Taxes][taxes] need to be applied to the withdrawals before showing the final value.
4. __List__ of a person's investments
    1. [x] This list should have pagination.

### Gain Calculation

Formula used: Final amount = Initial amount * (1 + interest / months) ^ months

### Taxation

The tax percentage changes according to the age of the investment and its applied only to the gains:
* If it is less than one year old, the percentage will be 22.5% (tax = 45.00).
* If it is between one and two years old, the percentage will be 18.5% (tax = 37.00).
* If older than two years, the percentage will be 15% (tax = 30.00).
