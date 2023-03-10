<?php

namespace App\Providers;

use App\Http\Requests\BaseRequestValidator;
use Illuminate\Support\ServiceProvider;

class BaseRequestValidatorProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->resolving(BaseRequestValidator::class, function ($baseRequestValidator, $app) {
            $baseRequestValidator = BaseRequestValidator::createFrom($app['request'], $baseRequestValidator);
            $baseRequestValidator->setApp($app);
        });

        $this->app->afterResolving(BaseRequestValidator::class, function (BaseRequestValidator $baseRequestValidator) {
            $baseRequestValidator->extendValidator();
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
