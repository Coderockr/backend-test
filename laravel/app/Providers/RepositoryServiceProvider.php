<?php

namespace App\Providers;

use App\Interfaces\InvestmentRepositoryInterface;
use App\Interfaces\OwnerRepositoryInterface;
use App\Repositories\InvestmentRepository;
use App\Repositories\OwnerRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(InvestmentRepositoryInterface::class, InvestmentRepository::class);
        $this->app->bind(OwnerRepositoryInterface::class, OwnerRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
