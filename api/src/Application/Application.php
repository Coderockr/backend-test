<?php
declare(strict_types=1);

namespace Application;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

final class Application
{
    private App $app;


    private function __construct(App $app)
    {
        $this->app = $app;
    }


    public static function create(App $app): self
    {
        $app->setBasePath('/backend-test/api');
        $app->addErrorMiddleware(self::isDevelopmentMode(), true, true);

        $application = new self($app);
        $application->defineRoutes();

        return $application;
    }


    public static function isDevelopmentMode(): bool
    {
        return $_SERVER['REMOTE_ADDR'] === '127.0.0.1';
    }


    public function run(): void
    {
        $this->app->run();
    }


    private function defineRoutes(): void
    {
        $this->app->get(
            '',
            function(
                ServerRequestInterface|Request $request,
                ResponseInterface|Response $response,
                array $args
            ) {
                $json = json_encode(['message' => 'Welcome to the Event Manager API']);
                $response->getBody()->write($json);

                return $response->withHeader('Content-Type', 'application/json');
            }
        );
    }

}