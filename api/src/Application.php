<?php
declare(strict_types=1);

namespace Api;

use Api\Controllers\EventController;
use Api\Controllers\IndexController;
use Slim\App;

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
        return ($_SERVER['REMOTE_ADDR'] ?? null) === '127.0.0.1'
               || str_starts_with($_SERVER['HTTP_HOST'] ?? '', 'localhost');
    }


    public function run(): void
    {
        $this->app->run();
    }


    private function defineRoutes(): void
    {
        $this->app->get('', [IndexController::class, 'index']);
        $this->app->get('/event', [EventController::class, 'list']);
        $this->app->get('/event/{id}', [EventController::class, 'details']);
    }

}