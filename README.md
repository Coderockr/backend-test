<h1 align="center">Backend Test Project</h1>

<h3 align="center">Laravel project in Laradock environment</h3>
<h5 align="center">
    Documentation: 
    <a href="https://laravel.com/docs/8.x">🔗 Laravel</a>
    <a href="https://laradock.io/documentation/">🔗 Laradock</a>
</h5>
<h5 align="center">
    <img src="https://img.shields.io/static/v1?label=Laravel&message=v8&color=blue"/>
    <img src="https://img.shields.io/static/v1?label=php&message=v7.4.28&color=blue"/>
    <img src="https://img.shields.io/static/v1?label=npm&message=v8.4.1&color=blue"/>
    <img src="https://img.shields.io/static/v1?label=composer&message=v2.2.6&color=blue"/>
</h5>

## Requirements
You must have [Docker](https://www.docker.com) installed on your machine and accept the Pull Request from development branch.

## Project access

#### Access the project root directory
```bash
$ cd backend-test
```
## Initial settings
#### Copy the .env.example into the project root directory
```bash
$ cp .env.example .env
```
#### Access the laradock directory
```bash
$ cd laradock
```
#### Copy .env.example into laradock directory
```bash
$ cp .env.example .env
```
## Docker Settings
#### Environment build
```bash
$ docker-compose build php-fpm workspace
```
```bash
$ docker-compose up -d nginx mysql phpmyadmin
```
#### Exec container
```bash
$ docker-compose exec workspace bash
```
## General Settings
#### Install dependencies
```bash
$ composer install
```
## Database Settings
You must create a database with the following information:
#### 
```bash
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=coderockr
DB_USERNAME=root
DB_PASSWORD=root
```
#### Run migrations
```bash
$ php artisan migrate
```
#### Populate database with seeders
```bash
$ php artisan db:seed
```
## Final Settings
#### Install dependencies
```bash
$ npm install
```
## General information
#### The API is running on [localhost](http://localhost:8888) on port 8888
#### The phpmyadmin database manager is running on [localhost](http://localhost:8081) on port 8081

## Tests
```bash
$ php artisan test
```

## API Documentation
#### The API Documentation is running on [localhost:8888/docs](http://localhost:8888/docs)




