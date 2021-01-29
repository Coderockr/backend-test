<?php
declare(strict_types=1);

use Api\Application\Application;
use DI\Bridge\Slim\Bridge;

require __DIR__ . '/../vendor/autoload.php';

// Create DI container holding services
$containerBuilder = new \DI\ContainerBuilder;
$containerBuilder->addDefinitions(__DIR__ . '/src/Application/services.php');
$container = $containerBuilder->build();

// Instantiate application with its respective services
$slimApp     = Bridge::create($container);
$application = Application::create($slimApp);

// Run application
$application->run();
