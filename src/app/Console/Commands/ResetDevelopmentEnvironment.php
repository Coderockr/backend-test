<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ResetDevelopmentEnvironment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'develop:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Drop database, run migrations and seeds';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Reseting database to development...');
        $bar = $this->output->createProgressBar(3);
        $bar->start();
        \Artisan::call('cache:clear');
        $bar->advance();
        \Artisan::call('migrate:fresh');
        $bar->advance();
        \Artisan::call('db:seed --class=Fakes');
        $bar->finish();
        $this->info(PHP_EOL . 'Development environment set succesfully' . PHP_EOL);
        return 0;
    }
}
