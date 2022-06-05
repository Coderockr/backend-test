<?php

namespace Illuminate\Console\Scheduling;

use Illuminate\Console\Application;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'schedule:test')]
class ScheduleTestCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'schedule:test {--name= : The name of the scheduled command to run}';

    /**
     * The name of the console command.
     *
     * This name is used to identify the command during lazy loading.
     *
     * @var string|null
     *
     * @deprecated
     */
    protected static $defaultName = 'schedule:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run a scheduled command';

    /**
     * Execute the console command.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    public function handle(Schedule $schedule)
    {
        $commands = $schedule->events();

        $commandNames = [];

        foreach ($commands as $command) {
            $commandNames[] = $command->command ?? $command->getSummaryForDisplay();
        }

        if (empty($commandNames)) {
            return $this->comment('No scheduled commands have been defined.');
        }

        if (! empty($name = $this->option('name'))) {
            $commandBinary = Application::phpBinary().' '.Application::artisanBinary();

            $matches = array_filter($commandNames, function ($commandName) use ($commandBinary, $name) {
                return trim(str_replace($commandBinary, '', $commandName)) === $name;
            });

            if (count($matches) !== 1) {
                return $this->error('No matching scheduled command found.');
            }

            $index = key($matches);
        } else {
            $index = array_search($this->choice('Which command would you like to run?', $commandNames), $commandNames);
        }

        $event = $commands[$index];

        $this->line('<info>['.Carbon::now()->format('c').'] Running scheduled command:</info> '.$event->getSummaryForDisplay());

        $event->run($this->laravel);
    }
}
