<?php

namespace App\Domains\Home\Providers;

use Illuminate\Support\ServiceProvider;

class DomainServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->registerRoutes();
    }

    protected function registerRoutes()
    {
        $this->app->register(RouteServiceProvider::class);
    }

}
