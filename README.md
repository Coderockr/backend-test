# Back End Test Project <img src="https://coderockr.com/assets/images/coderockr.svg" align="right" height="50px" />

# Desafio Back-End

### Installing

 - PHP (^8.0);
 - Laravel (9.x)
 - Composer
 - phpunit

Clone the repository and run
> composer install
- `Composer Install`: Install the application dependencies, [click here](https://getcomposer.org/) if you don't have composer installed.
<p>Make a copy of the <b>.env.example</b> file, setting it to <b>.env</b> so that you can
define application environment variables. Below will be the pre-defined information that you will have to fill in with the information of the database that you will use.</p>

>DB_CONNECTION=mysql <br>
DB_HOST=127.0.0.1 <br>
DB_PORT=3306 <br>
DB_DATABASE=banco_de_dados <br>
DB_USERNAME=usuario_banco_de_dados <br>
DB_PASSWORD=senha_banco_de_dados <br>

<p>After defining the necessary data to connect to your database, run the following command to create the tables.</p>

> php artisan migrate

 ### Initializing the application

To start the application, by default, Laravel will start at the address (http://localhost:8000), executing the command
> php artisan serve

 ### API Documentation
To view the documentation, go to https://documenter.getpostman.com/view/24612120/2s8YsqVaS6
