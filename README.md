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

### Email configuration

[Click here](https://mailtrap.io) and register on the Mailtrap.io platform. After that, it is necessary to create an inbox and select the integration with Laravel 7+. After that, you must copy and replace the old keys with the new ones, in the .env file, as the following example.

```
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=XXXXX
MAIL_PASSWORD=XXXXX
MAIL_ENCRYPTION=tls
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

To make the requests, it is necessary to register, through the API. With this, a token will be generated that will serve as authentication for the other requests. The login should only be done if at some point there was a logout.

## Improvements

Create more tests, generate documentation via Swagger and creating the docker-compose file.

## Api Documentation

BASE_URL = http://127.0.0.1:8000/api/v1/ <br><br>
[POST] BASE_URL/register <br>
<hr>
Description: Creates a user account and assign its a token to do the other program actions. <br><br>
Headers: Accept: application/json <br><br>
Body Parameters: <br><br>
<ul>
    <li>name (required) (string)</li>
    <li>email (required) (string) (unique)</li>
    <li>password (required) (string) (must match with password_confirmation)</li>
    <li>password_confirmation (required) (string) (must match with password_confirmation)</li>
</ul>
Status: <br><br>
<ul>
    <li>(201) Account created</li>
    <li>(422) Validation body parameters error</li>
</ul>
<br><br><br>
[POST] BASE_URL/logout
<hr>
    Description: Logout the user and delete its token.<br><br>
    Headers: Accept: application/json<br><br>
    Authorization: Bearer Token<br><br>
    Status: <br><br>
<ul>
        <li>(200) Logged out</li>
        <li>(401) Unauthorized</li>
</ul>
<br><br><br>
[POST] BASE_URL/login
<hr>
    Description: Login a user in the program.<br><br>
    Headers: Accept: application/json<br><br>
    Body Parameters:<br><br>
<ul>
        <li>email (required) (string)</li>
        <li>password (required) (string) (must match with password_confirmation)</li>
</ul>
    Status:<br><br>
<ul>
        <li>(201) Logged in</li>
        <li>(401) Unauthorized</li>
        <li>(422) Validation body parameters error</li>    
    </ul>
<br><br><br>
[POST] BASE_URL/investments
<hr>
    Description: Create a investment assigned to the user.<br><br>
    Headers: Accept: application/json<br><br>
    Authorization: Bearer Token<br><br>
    Body Parameters:<br><br>
<ul>
        <li>amount (required) (numeric) (between: 0 - 999999.99) (regex:/^-?[0-9]+(?:\.[0-9]{1,2})?$/)</li>
        <li>inserted_at (required) (date) (before_or_equal: today) (date_format: Y-m-d)</li>
</ul>
    Status:<br><br>
<ul>
        <li>(201) Investment created</li>
        <li>(302) Validation body parameters error</li>
        <li>(401) Unauthorized</li>
    </ul>
<br><br><br>
[GET] BASE_URL/investments
<hr>
    Description: Get a paginated list with all the user investment.<br><br>
    Headers: Accept: application/json<br><br>
    Authorization: Bearer Token<br><br>
    Status:<br><br>
<ul>
        <li>(200) Ok</li>
        <li>(401) Unauthorized</li>
    </ul>
<br><br><br>
[GET] BASE_URL/investments/{id}
<hr>
    Description: Show the informations (amount and expected balance) about the investiment with the id passed in the url.<br><br>
    Headers: Accept: application/json<br><br>
    Authorization: Bearer Token<br><br>
    Query params:<br><br>
    <ul>
        <li>id (required) (integer)</li>
    </ul>
    Status:<br><br>
<ul>
        <li>(200) Ok</li>
        <li>(401) Unauthorized</li>
        <li>(404) Not Found</li>
    </ul>
<br><br><br>
[POST] BASE_URL/investments/{id}/withdrawal
<hr>
    Description: Withdrawal an investment with the id passed in the url.<br><br>
    Headers: Accept: application/json<br><br>
    Authorization: Bearer Token<br><br>
    Query params:<br><br>
    <ul>
        <li>id (required) (integer)</li>
    </ul>
    Status:<br><br>
<ul>
        <li>(201) Successfull investment withdrawal</li>
        <li>(401) Unauthorized</li>
        <li>(404) Not Found</li>
</ul>
