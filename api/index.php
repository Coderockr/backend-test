<?php
declare(strict_types=1);

use Application\Application;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

/**
 * Instantiate App
 *
 * In order for the factory to work you need to ensure you have installed
 * a supported PSR-7 implementation of your choice e.g.: Slim PSR-7 and a supported
 * ServerRequest creator (included with Slim PSR-7)
 */
$app = AppFactory::create();

// Instantiate Application
$application = Application::create($app);

// Run application
$application->run();
