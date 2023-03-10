<?php

namespace App\Console;

use App\Jobs\ApplyRateInvestment;
use App\Service\InvestmentsService;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \Knuckles\Scribe\Commands\GenerateDocumentation::class,
        \Knuckles\Scribe\Commands\MakeStrategy::class,
        \Knuckles\Scribe\Commands\Upgrade::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->job(new ApplyRateInvestment($this->app->make(InvestmentsService::class)))->daily();
//        $schedule->call()->daily();
    }
}
