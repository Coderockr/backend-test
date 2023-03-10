<?php

namespace App\Providers;

use App\Repositories\Investor\InvestorRepository;
use App\Repositories\Investor\InvestorRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class InvestorRepositoryProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return string[]
     */
    public function boot()
    {
        return [InvestorRepositoryInterface::class];
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(InvestorRepositoryInterface::class, InvestorRepository::class);
    }
}
