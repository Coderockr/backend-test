<?php

namespace App\Domains\System\Providers;

use App\Domains\System\Http\Routes\Routes;
use Illuminate\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{

    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Domains\System\Http\Controllers';

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function register()
    {
        (new Routes([
            'namespace' => $this->namespace,
            'prefix' => 'system',
            'group' => 'api',
            'middleware'=>'jwt.verify'
        ]))->register();
    }
}
