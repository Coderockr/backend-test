<?php

namespace App\Domains\Investment\Http\Routes;

use App\Support\Http\Routing\RouteFile;

class Routes extends RouteFile
{

    protected function routes()
    {
        $router = $this->router;

        $router->group(['prefix' => 'moves'], function() use ($router) {
            $router->get('/', 'MoveController@getItems');
            $router->post('/gain', 'MoveController@gain');
            $router->get('/item/{id}', 'MoveController@getItem');
            $router->post('/create', 'MoveController@create');
            $router->put('/update', 'MoveController@update');
            $router->put('/delete','MoveController@delete');
        });

    }
}
