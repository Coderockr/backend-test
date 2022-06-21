<?php

require 'vendor/autoload.php';

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

$config = parse_ini_file('configs.ini', true);
$configs = [
    'settings' => $config[$config['environment']]
];
define("ENV", $config['environment']);
error_reporting($configs['settings']['errorReporting']);

$container = new \Slim\Container($configs);

/**
 * Converte os Exceptions entro da Aplicação em respostas JSON
 */
$container['errorHandler'] = function ($c) {
    return function ($request, $response, $exception) use ($c) {
        $statusCode = $exception->getCode() ? $exception->getCode() : 500;
        return $c['response']->withStatus($statusCode)
            ->withHeader('Content-Type', 'Application/json')
            ->withJson(["message" => $exception->getMessage()], $statusCode);
    };
};
/**
 * Converte os Exceptions de Erros 404 - Not Found
 */
$container['notFoundHandler'] = function ($container) {
    return function ($request, $response) use ($container) {
        return $container['response']
            ->withStatus(404)
            ->withHeader('Content-Type', 'Application/json')
            ->withJson(['message' => 'Page not found']);
    };
};
/**
 * Converte os Exceptions de Erros 405 - Not Allowed
 */
$container['notAllowedHandler'] = function ($c) {
    return function ($request, $response, $methods) use ($c) {
        return $c['response']
            ->withStatus(405)
            ->withHeader('Allow', implode(', ', $methods))
            ->withHeader('Content-Type', 'Application/json')
            ->withHeader("Access-Control-Allow-Methods", implode(",", $methods))
            ->withJson(["message" => "Method not Allowed; Method must be one of: " . implode(', ', $methods)], 405);
    };
};

$isDevMode = $config['environment'] != 'prod';
$isDevMode = true;
$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__ . "/src/Models/Entities"), $isDevMode);
$conn = [
    'dbname' => $configs['settings']['dbname'],
    'user' => $configs['settings']['user'],
    'password' => $configs['settings']['password'],
    'host' => $configs['settings']['host'],
    'driver' => $configs['settings']['driver'],
    'charset' => 'utf8',
];
/**
 * Cria o Entity Manager do doctrine
 */
$entityManager = EntityManager::create($conn, $config);
$conn = $entityManager->getConnection();
$conn->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
$config = new \Doctrine\ORM\Configuration();
$config->addCustomDatetimeFunction('DATE', 'App\Helpers\DoctrineDate');

$container['em'] = $entityManager;
/**
 * Carrega os Controllers pra dentro do Slim
 */
foreach (glob('src/Controllers/*') as $filename) {
    $filename = explode('/', $filename);
    $controller = str_replace('.php', '', end($filename));
    $container[$controller] = function () use ($controller, $container) {
        $class = '\\App\\Controllers\\' . $controller;
        return new $class($container['em']);
    };
}
$app = new \Slim\App($container);
//forçar barra no final
$_SERVER["REQUEST_URI"] = substr($_SERVER["REQUEST_URI"], -1) == '/' ? $_SERVER["REQUEST_URI"] : $_SERVER["REQUEST_URI"] . '/';
