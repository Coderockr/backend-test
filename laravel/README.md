## Special build instructions

Install dependencies

```
$ composer install
```

Create .env file

```
$ cp .env.example .env
```

Configure .env database fields

E.g:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=teste
DB_USERNAME=root
DB_PASSWORD=123456789
```

Generate Laravel apllication key

```
$ php artisan key:generate
```

Make the database table migration

```
$ php artisan migrate
```

You can now run the application with 

```
$ php artisan serve
```

You can run unit tests executing

```
vendor/bin/phpunit
```

## API documentation

[Link API](https://documenter.getpostman.com/view/8026812/UVRDF5UN)