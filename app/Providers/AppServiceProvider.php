<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Eloquent\InvestmentRepository;
use App\Repositories\Contracts\Investment as InvestmentContract;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(InvestmentContract::class, InvestmentRepository::class);
    }
}
