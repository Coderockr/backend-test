# Back End Test Project <img src="https://coderockr.com/assets/images/coderockr.svg" align="right" height="50px" />

## Getting Started

To run this project, it's necessary PHP, Composer and Mysql. For elaboration, the following versions were used (which serve as a suggestion for use):

- PHP 8.1.6
- Composer 2.3.5
- MySQL 8.0.29

## Main Packages

Token authentication creation: [Sanctum](https://laravel.com/docs/9.x/sanctum)
Sending emails: [Mailtrap.io](https://mailtrap.io/inboxes/1766647/messages/2812274287)

## How to run

### Creating a file with environment variables

The first point is to create a copy of the .env.example file as .env:

```
cp .env.example .env
```

This will publish a file that contains the necessary environment variables.

### Editing the .env file values

In particular, you must edit the values ​​for the following variables:

```
APP_NAME=XXXX

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=XXXX
DB_USERNAME=XXXX
DB_PASSWORD=XXXX
```

> It is necessary to create a database with the name defined in DB_DATABASE, and the DB_USERNAME and DB_PASSWORD must be the credentials of a user with full privileges. For this, you can read more in ["How to create a MySQL user with all privileges"](https://phoenixnap.com/kb/how-to-create-new-mysql-user-account-grant-privileges).

### Installing project dependencies

When running the following command, all project dependencies will be published in the *vendor* folder.

```
composer install
```

### Generating a unique key for the application

Then, it is necessary to generate a unique key for the application through:

```
php artisan key:generate
```

### Database

With the database created previously, it is necessary to run the *migrations* that will generate the project tables, through:

```
php artisan migrate
```

### Opening the server

Finally, you must generate a port that will be running the server.

```
php artisan serve
```

This will cause a *bind* to [port 8000 of *localhost*](http://localhost:8000/).

## How to test

To run the built tests, you need to run:

```
php artisan test
```

## Project Flow


## Improvements

Create more tests and generate documentation via Swagger

https://mailtrap.io/inboxes/1766647/messages/2812274287

Login

Create inbox

Select Laravel 7+

Copy the keys

Replace the variables in the .env file, using the new ones.

BASE_URL = http://127.0.0.1:8000/api/v1/

[POST] BASE_URL/register


    Description: Creates a user account and assign its a token to do the other program actions.

    Headers: Accept: application/json

    Body Parameters:

        * name (required) (string)

        * email (required) (string) (unique)

        * password (required) (string) (must match with password_confirmation)

        * password_confirmation (required) (string) (must match with password_confirmation)

    Status:

        * (201) Account created

        * (422) Validation body parameters error


[POST] BASE_URL/logout
    Description: Logout the user and delete its token.
    Headers: Accept: application/json
    Authorization: Bearer Token
    Status:
        200 - Logged out
        401 - Unauthorized

[POST] BASE_URL/login
    Description: Login a user in the program.
    Headers: Accept: application/json
    Body Parameters:
        - email (required) (string)
        - password (required) (string) (must match with password_confirmation)
    Status:
        201 - Logged in
        401 - Unauthorized
        422 - Validation body parameters error      

[POST] BASE_URL/investments
    Description: Create a investment assigned to the user.
    Headers: Accept: application/json
    Authorization: Bearer Token
    Body Parameters:
        - amount (required) (numeric) (between: 0 - 999999.99) (regex:/^-?[0-9]+(?:\.[0-9]{1,2})?$/)
        - inserted_at (required) (date) (before_or_equal: today) (date_format: Y-m-d)
    Status:
        201 - Investment created
        302 - Validation body parameters error
        401 - Unauthorized

[GET] BASE_URL/investments
    Description: Get a paginated list with all the user investment.
    Headers: Accept: application/json
    Authorization: Bearer Token
    Status:
        200 - Ok
        401 - Unauthorized

[GET] BASE_URL/investments/{id}
    Description: Show the informations (amount and expected balance) about the investiment with the id passed in the url.
    Headers: Accept: application/json
    Authorization: Bearer Token
    Query params:
        - id (required) (integer)
    Status:
        200 - Ok
        401 - Unauthorized
        404 - Not Found

[POST] BASE_URL/investments/{id}/withdrawal
    Description: Withdrawal an investment with the id passed in the url.
    Headers: Accept: application/json
    Authorization: Bearer Token
    Query params:
        - id (required) (integer)
    Status:
        201 - Successfull investment withdrawal
        401 - Unauthorized
        404 - Not Found
