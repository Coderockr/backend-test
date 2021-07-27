#  Coderockr Events API

## Scope
API for a social networking events. 
The test description can be found in the `test-description.md` file in the project root.

Aboute the project:
-   This project is based on [Laravel Framework](https://github.com/laravel/laravel).
-   An auth API, using [tymon/jwt-auth](https://github.com/tymondesigns/jwt-auth) to manage the Authorization Tokens.
-   Database seeding.
-   [Faker](https://github.com/fzaninotto/Faker) helps to generate fake data for the migrations and tests.
-   Tests with [PHPUnit](https://laravel.com/docs/5.8/testing).
-   A base ApiController to standardizing responses.

### Setup

#### Clone the repository

#### Install PHP dependencies:
```php
composer install
```

#### Create your environment file:
```bash
cp .env.example .env
```

####  Update your .env file:
-   DB_DATABASE (your database, ex: "coderockr_db")
-   DB_USERNAME (your db username, ex: "root")
-   DB_PASSWORD (your db password, ex: "")

#### Generate an app key:
```php
php artisan key:generate
```

#### Generate JWT keys for the .env file:
```php
php artisan jwt:secret
```

#### Run the database migrations:
```php
php artisan migrate
```

#### Start the Laravel PHP Server
Use the following command in the the project's root directory:
```php
php artisan serve
```

### Additional Tips

#### Database Seeding
To generate sample data to work with, you can seed the database:
```php
php artisan migrate:refresh --seed --force
```

#### Seeded User
If you seeded the database, you can use these credentials to login:
Email: `user@test.dev`
Password: `password`

#### Automated Tests
This project supports tests. To run the tests use:
```bash
vendor/bin/phpunit
```

#### Postman API Collection
Import the `postman-api-collection.json` file from the project's root directory in your Postman to get the API routes collection.
 