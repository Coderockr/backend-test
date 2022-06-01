<?php

namespace App\Domains\Person\Providers;

use App\Domains\Person\Database\Migrations\CreateRolesTable;
use App\Domains\Person\Database\Migrations\CreatePeopleTable;
use App\Domains\Person\Database\Migrations\CreatePhonesTable;
use App\Domains\Person\Database\Migrations\CreateAccountsTable;
use Illuminate\Support\ServiceProvider;
use Migrator\MigratorTrait;

class DomainServiceProvider extends ServiceProvider
{
    use MigratorTrait;

    public function register()
    {
        $this->registerRoutes();
        $this->registerMigrations();
    }

    protected function registerRoutes()
    {
        $this->app->register(RouteServiceProvider::class);
    }

     /**
     * @return void
     */
    protected function registerMigrations()
    {
        $this->migrations([
            CreateRolesTable::class,
            CreatePeopleTable::class,
            CreatePhonesTable::class,
            CreateAccountsTable::class
        ]);
    }
}
