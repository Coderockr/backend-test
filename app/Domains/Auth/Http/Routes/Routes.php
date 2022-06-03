<?php

namespace App\Domains\Auth\Http\Routes;

use App\Support\Http\Routing\RouteFile;

class Routes extends RouteFile
{

    protected function routes()
    {
        $router = $this->router;

        $router->post('/login', 'AuthController@authenticate');
        $router->get('/ping', function() {})->middleware('jwt.verify');
    }
}
