<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:admin:create {username} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an admin to administrate Investments';

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
        $this->info('Running create admin user...');
        $admin = new \App\Models\AdminUser;
        $admin->user = $this->argument('username');
        $admin->password = password_hash($this->argument('password'), PASSWORD_DEFAULT);
        $admin->save();
        $this->info('User created succesfully...' . PHP_EOL);
    }
}
