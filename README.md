# Back End Test Project <img src="https://coderockr.com/assets/images/coderockr.svg" align="right" height="50px" />

### In this test, must be built an API that receives an investment, and provide a profit to this investment every month, in the same day of the investment creation.

# To execute the project in your machine, follow the steps below

```
# clone the repository
$ git clone git@github.com:thalesmengue/backend-test.git

# access the project folder in your terminal or in your IDE 

# install the dependencies
$ composer install

# copy the .env file of the project
$ cp .env.example .env

# set environment variables of your database in the .env file

# create a new key to the application
$ php artisan key:generate

# install the laravel passport
$ php artisan passport:install

#get the client secret for the client 2, and put on your .env file on the "CLIENT_SECRET"

# execute the command below to run the migrations
$ php artisan migrate

# execute the following code block to populate your user database with the Seeder
$ php artisan db:seed

# execute the below command to acess the API
$ php artisan serve

```

### After you followed the steps above, must have been created a user with the following infos

```bash
login: 'admin@test.com'
password: 'password'
```

## commands

### I created the commands below to create a random investment and to generate the profits for the investments.

```bash
php artisan create:investment {amount} {user_id} {date}

example:
php artisan create:investment 300.25 1 2022-2-3
```

```bash
php artisan generate:profit {investmentId}

example:
php artisan generate:profits 3
```

*obs: on a real situation, the ideal scenario should have been created a job to handle monthly the profit increase*

# Routes

## Login routes

| Method HTTP | Endpoint       | Description            |
|-------------|----------------|------------------------|
| POST        | `/oauth/token` | Return the oauth token |
| POST        | `/api/login`   | Use to login the user  |

## Investment routes

| Method HTTP | Endpoint                  | Description                       |
|-------------|---------------------------|-----------------------------------|
| GET         | `/api/investments`        | Return all investments registered |
| POST        | `/api/investments/create` | Register a new investment         |

## Withdraw routes

| Method HTTP | Endpoint                               | Description                |
|-------------|----------------------------------------|----------------------------|
| GET         | `/api/withdraws/{investmentId}/`       | Return a specific withdraw |
| GET         | `/api/withdraws/{investmentId}/create` | Create a new withdraw      |

## Profit routes

| Method HTTP | Endpoint                             | Description              |
|-------------|--------------------------------------|--------------------------|
| GET         | `/api/profits/{investmentId}/`       | Return a specific profit |
| GET         | `/api/profits/{investmentId}/create` | Create a new profit      |

## Login routes

### [There are two basic login routes on laravel passport, the first one `/oauth/token`, and the second one `/api/login`, the first one works fine on localhost, but the second one need nginx to work in his best]

### the first one works fine without nginx, but need to pass the parameters below to get the bearer_token and access the other routes

```bash

{
        "grant_type": "password",
        "client_id": "2",
        "client_secret": ["YOUR_CLIENT_SECRET"],
        "username": "admin@test.com",
    	"password": "password",
    	"scope": "*"
}

```

*obs: on "YOUR_CLIENT_SECRET" need to be put the client secret generate by passport on installation*

#### technologies utilized to create the API

* Laravel 9.x (to create the API) [https://laravel.com/docs/9.x]
* Laravel Passport (to make the auth) [https://laravel.com/docs/9.x/passport]

*obs: the profit routes were made as a test, because the generate profit command are in charge of creating a new profit*
