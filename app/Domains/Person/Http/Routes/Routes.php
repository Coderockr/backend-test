<?php

namespace App\Domains\Person\Http\Routes;

use App\Support\Http\Routing\RouteFile;

class Routes extends RouteFile
{

    protected function routes()
    {
        $router = $this->router;

        $router->get('/','PersonController@getItems');
        $router->get('/item/{id}','PersonController@getItem');
        $router->get('/account/{number}','PersonController@getItemByAccount');
        $router->post('/create','PersonController@create');
        $router->put('/update','PersonController@update');
        $router->put('/delete','PersonController@delete');

        $router->group(['prefix' => 'phones'], function() use ($router) {
            $router->get('/', 'PhoneController@getItems');
            $router->get('/item/{id}', 'PhoneController@getItem');
            $router->post('/create', 'PhoneController@create');
            $router->put('/update', 'PhoneController@update');
            $router->put('/delete','PhoneController@delete');
        });

        $router->group(['prefix' => 'roles'], function() use ($router) {
            $router->get('/', 'RoleController@getItems');
            $router->get('/item/{id}', 'RoleController@getItem');
            $router->post('/create', 'RoleController@create');
            $router->put('/update', 'RoleController@update');
            $router->put('/delete','RoleController@delete');
        });
    }
}
