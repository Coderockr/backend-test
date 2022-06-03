<?php

namespace App\Domains\Log\Providers;

use App\Domains\Log\Database\Migrations\CreateLogsTable;
use Illuminate\Support\ServiceProvider;
use Migrator\MigratorTrait;

class DomainServiceProvider extends ServiceProvider
{
    use MigratorTrait;

    /**
     * @return void
     */
    public function register()
    {
        $this->registerRoutes();
        $this->registerMigrations();
    }

    /**
     * @return void
     */
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
            CreateLogsTable::class
        ]);
    }

}
