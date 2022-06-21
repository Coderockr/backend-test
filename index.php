<?php
header("Access-Control-Allow-Origin: *");
require 'bootstrap.php';

foreach (glob(__DIR__ . "/routes/*.php") as $filename) {
    require $filename;
}

$app->run();

