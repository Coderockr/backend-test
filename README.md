<h1 align="center">Backend Test Project</h1>


## Requirements
It is necessary to have the docker installed on your computer. [Go to docker website](https://www.docker.com)

### Passo a passo

Acess project root directory 
```sh
cd backend-test
```

Make a copy of the .env.example to .env 
```sh
cp .env.example .env
```

Run docker-compose command to start containers
```sh
docker-compose up -d
```

Get into app bash with the above command
```sh
docker-compose exec app bash
```
#### On app bash 
Install composer dependencies
```sh
composer install
```

Generate Laravel app key
```sh
php artisan key:generate
```

Run migrations
```sh
php artisan migrate 
```

Run tests
```sh
php artisan test 
```

Api base url
[http://localhost:8100/api/v1](http://localhost:8100/api/v1)

Api Documentation
[http://localhost:8100](http://localhost:8100/)
