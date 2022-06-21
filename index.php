<?php
header("Access-Control-Allow-Origin: *");
require 'bootstrap.php';
date_default_timezone_set('America/Sao_Paulo');

foreach (glob(__DIR__ . "/routes/*.php") as $filename) {
    require $filename;
}

$app->run();

