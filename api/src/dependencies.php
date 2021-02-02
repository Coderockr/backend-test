<?php
declare(strict_types=1);

use Api\Controllers\EventController;
use Api\Controllers\IndexController;
use Api\Filters\EventFilter;
use Api\Tables\AddressTable;
use Api\Tables\EventTable;
use Api\Tables\UserTable;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Sql\Where;
use Laminas\Db\TableGateway\TableGateway;
use Psr\Container\ContainerInterface;

// @codeCoverageIgnoreStart
return [
    // Application
    Adapter::class              => function(ContainerInterface $container): Adapter {
        return new Adapter($container->get('db'));
    },

    // Controllers
    IndexController::class      => function(ContainerInterface $container): IndexController {
        return new IndexController(null, null);
    },
    EventController::class      => function(ContainerInterface $container): EventController {
        return new EventController(
            $container->get(EventTable::class),
            $container->get(EventFilter::class),
        );
    },

    // Filters
    EventFilter::class          => function(ContainerInterface $container): EventFilter {
        return new EventFilter(new Where());
    },

    // Table Gateways
    AddressTable::TABLE_GATEWAY => function(ContainerInterface $container) {
        return new TableGateway(
            AddressTable::DB_TABLE,
            $container->get(Adapter::class)
        );
    },
    UserTable::TABLE_GATEWAY    => function(ContainerInterface $container) {
        return new TableGateway(
            UserTable::DB_TABLE,
            $container->get(Adapter::class)
        );
    },
    EventTable::TABLE_GATEWAY   => function(ContainerInterface $container) {
        return new TableGateway(
            EventTable::DB_TABLE,
            $container->get(Adapter::class)
        );
    },

    // Tables
    AddressTable::class         => function(ContainerInterface $container) {
        return new AddressTable($container->get(AddressTable::TABLE_GATEWAY));
    },
    UserTable::class            => function(ContainerInterface $container) {
        return new UserTable(
            $container->get(UserTable::TABLE_GATEWAY),
        );
    },
    EventTable::class           => function(ContainerInterface $container) {
        return new EventTable(
            $container->get(EventTable::TABLE_GATEWAY),
        );
    },
];