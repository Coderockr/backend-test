<?php

namespace App\Console\Commands;


use Illuminate\Console\Command;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\FileController;
use App\Content;
use Illuminate\Support\Facades\Hash;
use Log;


class UserAdd extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = "user:add {name} {email} {password}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Create a new user.";


        /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $aux = \DB::table('user')->where(
            'email', '=',  $this->argument('email')
        )->get()
        ->toArray();

        if (empty($aux)) {
            \DB::table('user')->insert(
                [
                    'name' => $this->argument('name'),
                    'email' => $this->argument('email'),
                    'password' => Hash::make($this->argument('password'))
                ]
            );
        }

    }
}

