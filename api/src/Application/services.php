<?php
declare(strict_types=1);

use Api\Application\IndexController;
use Psr\Container\ContainerInterface;

return [
    // Controllers
    IndexController::class => function(ContainerInterface $c): IndexController {
        return new IndexController();
    },
];