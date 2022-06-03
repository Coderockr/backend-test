<?php

namespace App\Domains\System\Providers;

use App\Domains\System\Database\Migrations\CreateAddressTable;
use App\Domains\System\Database\Migrations\CreateAgenciesTable;
use App\Domains\System\Database\Migrations\CreateModulesTable;
use App\Domains\System\Database\Migrations\CreateChildModulesTable;
use App\Domains\System\Database\Migrations\CreateJobsTable;
use App\Domains\System\Database\Migrations\CreateFailedJobsTable;
use App\Domains\System\Database\Migrations\CreateWhitelistTable;
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
            CreateAddressTable::class,
            CreateJobsTable::class,
            CreateFailedJobsTable::class,
        ]);
    }
}
