# Investment API

&nbsp;
## Description

This documentation has the purpose to guide the readers, on how to install and run the application, and some information about routes and the design thought through the development.
The idea of this API is to create, list and withdrawal investments which increases 0,52% per month.

&nbsp;
## How to Install

> Require [docker](https://docs.docker.com/get-docker/)

> Clone the repository [investment_api](https://github.com/bruno-holanda15/investment_api/tree/development)

&nbsp;
### RUN
Start the containers from docker-compose:
> `docker-compose up -d`

Enter inside de container:
> `docker-compose exec app bash` 

Install dependecies inside the container:
> `composer install` 

Generate the key to laravel app:
> `php artisan key:generate` 

Run migrations to construct database:
> `php artisan migrate && php artisan migrate --env=testing`

&nbsp;
## Routes
We use [laravel-request-docs](https://github.com/rakutentech/laravel-request-docs) to document and test this API via http://localhost:8989/request-docs, but above we are going to explain the flow to create investment:

- '/api/owner' - POST method - create an owner, passing e-mail(unique in database and helps us to validate) and name as parameters.

- '/api/investment' - POST method - create an investment, passing e-mail(the same e-mail used to create owner), creation_date (format 'Y-m-d' Example:2020-02-02), date could be a date in the past or today, amount (money invested).

- '/api/investment' - GET method - return the value expected from the investment in the current date.

- '/api/owners_investments' - GET method - return all the investments from owner - pass e-mail as parameter.

- '/api/withdrawal' - POST method - withdrawal the investment, passing the id of the investment and withdrawal_date(format 'Y-m-d' Example:2020-02-02).

## Tests
We use [pest](https://pestphp.com/docs/installation) to run tests developed inside tests/Feature folder,
run `composer pest` inside the container to verify assertions created at files tests.

## Format
We use [pint](https://laravel.com/docs/9.x/pint) to to ensure that your code style stays clean and consistent, run
`./vendor/bin/pint` inside our container.





