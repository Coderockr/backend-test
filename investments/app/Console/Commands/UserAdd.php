<?php

namespace App\Console\Commands;


use Illuminate\Console\Command;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\FileController;
use App\Content;
use Illuminate\Support\Facades\Hash;
use App\Models\Roles;
use App\Models\User;
use App\Models\Permissions;
use Log;


class UserAdd extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = "user:add {name} {email} {password} {role}";

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

        $roleId = $this->roleId($this->argument('role'));

        $aux = User::where(
            'email', '=',  $this->argument('email')
        )->get()
        ->toArray();

        if (empty($aux)) {
            $userId = User::create(
                [
                    'name' => $this->argument('name'),
                    'email' => $this->argument('email'),
                    'password' => Hash::make($this->argument('password'))
                ]
            );

            if ($userId) {
                Permissions::insert(
                    ['users_id' => $userId->id, 'roles_id' => $roleId]
                );
            }

            echo "Created!\n";
        }

    }

    public function roleId($role)
    {
        $rolesID = Roles::select('id')->where('name', '=', $role)->get()->toArray();

        if ($rolesID) {
            return $rolesID[0] ['id'];
        }

        exit(
            "Role not found in database - aborting insert\n"
        );
    }
}

