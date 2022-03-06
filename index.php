<?php

require __DIR__.'/vendor/autoload.php';

use App\Http\Router;
use Bootstrap\EnvironmentVariableLoader;

EnvironmentVariableLoader::load(__DIR__);

define('URL', getenv('APP_URL'));

$router = new Router(URL);

include __DIR__.'/routes/api.php';

$router->run()->sendResponse();