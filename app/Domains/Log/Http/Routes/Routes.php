<?php

namespace App\Domains\Log\Http\Routes;

use App\Support\Http\Routing\RouteFile;

class Routes extends RouteFile
{
    /**
     * @return void
     */
    protected function routes()
    {
        $router = $this->router;
        
        $router->get('/', 'LogController@getItems');

    }
}
