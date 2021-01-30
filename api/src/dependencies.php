<?php
declare(strict_types=1);

use Api\Application\Controllers\IndexController;
use Laminas\Db\Adapter\Adapter;
use Psr\Container\ContainerInterface;

// @codeCoverageIgnoreStart
return [
    // Controllers
    IndexController::class => function(ContainerInterface $c): IndexController {
        return new IndexController();
    },
    // Services
    Adapter::class => function(ContainerInterface $c): Adapter {
        return new Adapter($c->get('db'));
    },
];