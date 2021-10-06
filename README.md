# Back End Test Project
Backend test api for Coderock.

### Getting started
First make a clone of this repository.

`git clone https://github.com/paulohnj/backend-test.git`

> Change to files diretory  
`cd backend-test/investment/investments/`

> I used docker as dev environment  
`docker-composer up --build`

> Configure environment  
`cp .env.example .env`

>Migration and Seed database  
`php artisan migrate --seed`  

### Third-party libraries
In this project I'm using
- [Laravel Lumen](https://lumen.laravel.com/docs/8.x)
- [JWT](https://github.com/tymondesigns/jwt-auth)


### API 
Documentation for this API
[Here](http://104.248.11.98:8087/)

