<?php
require __DIR__ . "/vendor/autoload.php";

date_default_timezone_set('America/Sao_Paulo');

use Dotenv\Dotenv;
use WilliamCosta\DatabaseManager\Database;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

Database::config($_ENV['DB_HOST'],$_ENV['DB_NAME'],$_ENV['DB_USER'],$_ENV['DB_PASS'],$_ENV['DB_PORT']);
