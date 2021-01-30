<?php
declare(strict_types=1);

namespace Api\Tests\Unit\Application;

use Api\Application\Application;
use Api\Application\Controllers\IndexController;
use PHPUnit\Framework\TestCase;
use Slim\App;

final class ApplicationTest extends TestCase
{
    public function testBasePathIsSetCorrectly(): void
    {
        $slimApp = $this->createMock(App::class);

        $slimApp->expects(static::once())
                ->method('setBasePath')
                ->with('/backend-test/api');

        Application::create($slimApp);
    }


    public function testErrorMiddlewareIsAddedForDevelopmentCheckingIp(): void
    {
        $slimApp = $this->createMock(App::class);

        $oldIpIfExists = array_key_exists('REMOTE_ADDR', $_SERVER) ? $_SERVER['REMOTE_ADDR'] : false;

        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';

        $slimApp->expects(static::once())
                ->method('addErrorMiddleware')
                ->with(true, true, true);

        Application::create($slimApp);

        if ($oldIpIfExists === false) {
            unset($_SERVER['REMOTE_ADDR']);
        }
        else {
            $_SERVER['REMOTE_ADDR'] = $oldIpIfExists;
        }
    }


    public function testErrorMiddlewareIsAddedForDevelopmentCheckingHost(): void
    {
        $slimApp = $this->createMock(App::class);

        $oldHostIfExists = array_key_exists('HTTP_HOST', $_SERVER) ? $_SERVER['HTTP_HOST'] : false;

        $_SERVER['HTTP_HOST'] = 'localhost';

        $slimApp->expects(static::once())
                ->method('addErrorMiddleware')
                ->with(true, true, true);

        Application::create($slimApp);

        if ($oldHostIfExists === false) {
            unset($_SERVER['HTTP_HOST']);
        }
        else {
            $_SERVER['HTTP_HOST'] = $oldHostIfExists;
        }
    }


    public function testErrorMiddlewareIsAddedForNonDevelopment(): void
    {
        $slimApp = $this->createMock(App::class);

        $oldIpIfExists   = array_key_exists('REMOTE_ADDR', $_SERVER) ? $_SERVER['REMOTE_ADDR'] : false;
        $oldHostIfExists = array_key_exists('HTTP_HOST', $_SERVER) ? $_SERVER['HTTP_HOST'] : false;

        $_SERVER['REMOTE_ADDR'] = '0.0.0.0';
        $_SERVER['HTTP_HOST']   = 'local';

        $slimApp->expects(static::once())
                ->method('addErrorMiddleware')
                ->with(false, true, true);

        Application::create($slimApp);

        if ($oldIpIfExists === false) {
            unset($_SERVER['REMOTE_ADDR']);
        }
        else {
            $_SERVER['REMOTE_ADDR'] = $oldIpIfExists;
        }

        if ($oldHostIfExists === false) {
            unset($_SERVER['HTTP_HOST']);
        }
        else {
            $_SERVER['HTTP_HOST'] = $oldHostIfExists;
        }
    }


    public function testDefinedRoutes(): void
    {
        $slimApp = $this->createMock(App::class);

        $slimApp->expects(static::once())
                ->method('get')
                ->withConsecutive(
                    ['', [IndexController::class, 'index']]
                );

        Application::create($slimApp);
    }


    public function testApplicationIsRun(): void
    {
        $slimApp     = $this->createMock(App::class);
        $application = Application::create($slimApp);

        $slimApp->expects(static::once())->method('run');

        $application->run();
    }

}