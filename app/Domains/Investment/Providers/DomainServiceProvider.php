<?php

namespace App\Domains\Investment\Providers;

use App\Domains\Investment\Database\Migrations\CreateMovesTable;
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
            CreateMovesTable::class
        ]);
    }
}
