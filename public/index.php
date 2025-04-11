<?php
// Carrega o autoloader do Composer
require __DIR__ . '/../vendor/autoload.php';

// Configura o ambiente (desenvolvimento ou produção)
$environment = getenv('APP_ENV') ?: 'prod';
$debug = $environment !== 'prod';

// Inicializa o framework / lógica principal
if ($debug) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}

// Inicialização framework Symfony
use App\Kernel;
use Symfony\Component\HttpFoundation\Request;

$kernel = new Kernel($environment, $debug);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
