<?php

namespace App\Domains\Home\Http\Routes;

use App\Support\Http\Routing\RouteFile;

class Routes extends RouteFile
{

    protected function routes()
    {
        $router = $this->router;

        $router->get('/','HomeController@index');

    }
}
