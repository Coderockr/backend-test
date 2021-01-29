<?php
declare(strict_types=1);

namespace Api\Application;

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
               || strpos($_SERVER['HTTP_HOST'] ?? '', 'localhost') === 0;
    }


    public function run(): void
    {
        $this->app->run();
    }


    private function defineRoutes(): void
    {
        $this->app->get('', [IndexController::class, 'index']);
    }

}