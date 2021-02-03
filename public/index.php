<?php
declare(strict_types=1);

use Api\Application;
use DI\Bridge\Slim\Bridge;

require __DIR__ . '/../vendor/autoload.php';

// Create DI container holding configuration and dependencies
$containerBuilder = new \DI\ContainerBuilder;

$settingsPath = Application::isDevelopmentMode()
    ? __DIR__ . '/../api/config/settings.development.php'
    : __DIR__ . '/../api/config/settings.php';

$servicesPath = __DIR__ . '/../api/src/dependencies.php';

$containerBuilder->addDefinitions($settingsPath);
$containerBuilder->addDefinitions($servicesPath);
$container = $containerBuilder->build();

// Instantiate application with its respective services
$slimApp     = Bridge::create($container);
$application = Application::create($slimApp);

// Run application
$application->run();
