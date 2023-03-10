<?php

namespace App\Providers;

use App\Repositories\Investment\InvestmentRepository;
use App\Repositories\Investment\InvestmentRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class InvestimentRepositoryProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return string[]
     */
    public function boot()
    {
        return [InvestmentRepositoryInterface::class];
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(InvestmentRepositoryInterface::class, InvestmentRepository::class);
    }
}
