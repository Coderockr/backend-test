<?php

namespace App\Support\Http\Routing;

abstract class RouteFile
{
    protected $options;

    /*
     * @var
     */
    protected $router;

    /*
     * RouteFile constructor
     * @param array $options
     */
    public function __construct($options = [])
    {
        $this->options = $options;

        $this->router = app('router');
    }

    public function register()
    {
        $this->router->group($this->options, function () {
            $this->routes();
        });
    }

    abstract protected function routes();

}