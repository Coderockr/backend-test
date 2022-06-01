<?php

namespace App\Domains\System\Http\Routes;

use App\Support\Http\Routing\RouteFile;

class Routes extends RouteFile
{

    protected function routes()
    {
        $router = $this->router;

        $router->get('/queue','SystemController@getQueue');

    }
}
