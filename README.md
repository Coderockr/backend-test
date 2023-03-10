# Coderock test

###

## prerequisites
To run the project it is necessary to have already installed PHP, Composer and SQLite database, 
check on the projects page how to install each one

* [SQLite](https://sqlite.org/index.html)
* [PHP](https://www.php.net/manual/pt_BR/install.php)
* [Composer](https://getcomposer.org)


### Versions used
* SQLite 3.40
* PHP 8.0

<br>

## Dependencies and Framework
For this test I used the Lumen framework in version 10.x.  
I also used the Moneyphp library that implements the 'Money pattern'   
as described in [Fowler2002](https://www.moneyphp.org/en/stable/#fowler2002),
this library provides tools to easily store and use monetary values.  
Another library I used was Carbon PHP, also with the intention of facilitating operations with dates.  
And I used Scribe lib for generate API documentation.  
* [Carbon](https://carbon.nesbot.com/docs/)    
* [MoneyPHP](www.moneyphp.org/en/stable/)   
* [Scribe](https://scribe.knuckles.wtf/)  

[Test Description](https://github.com/Coderockr/backend-test)  
I used the MVC-like estructure from lumen framework and create Service and Repository layers,  
I used casts for monetary and dates fields in models, also to fields values for type, origin and  
investment status witch coming from an enums object.  
A job was created to run a function daily to apply a rate to investments that have been one month   
since the last application on the current date.  


## Installation

First we must clone the GitHub repository
```sh
git clone https://github.com/Jciel/backend-test.git
```

<br>
<br>

After the clone of the project, we can enter the project directory and install
the application's dependencies
```sh
cd backend-test

composer install
```
## Migrations

To run the migrations that will create the database structure, run the command
```sh
php artisan php artisan migrate
```

## Seed
To run the seeds to populate the database we can run the command
```sh
php artisan db:seed
```

<br>

After installing the dependencies, we can upload the project running locally with the command
```sh
php artisan serve
```

<br>

## API documentation

To create the API documentation we need to run the command

```
php artisan scribe:generate
```

After that the documentation will be available at the local address
```
http://127.0.0.1:8000/docs
```
