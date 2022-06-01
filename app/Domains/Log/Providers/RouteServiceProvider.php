<?php

namespace App\Domains\Log\Providers;

use App\Domains\Log\Http\Routes\Routes;
use Illuminate\Support\ServiceProvider;

/**
 * Description of RouteServiceProvider.
 *
 * @author Luis Henrique
 */
class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Domains\Log\Http\Controllers';

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function register()
    {
        (new Routes([
            'namespace' => $this->namespace,
            'prefix' => 'logs',
            'middleware'=>'jwt.verify'
        ]))->register();
    }
}
