<?php

namespace App\Domains\Auth\Providers;

use Illuminate\Support\ServiceProvider;
use Migrator\MigratorTrait;

class DomainServiceProvider extends ServiceProvider
{
    use MigratorTrait;

    public function register()
    {
        $this->registerRoutes();
    }

    protected function registerRoutes()
    {
        $this->app->register(RouteServiceProvider::class);
    }

}
