<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Roles;
use App\Models\Permissions;
use Auth;


class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        if (empty(Gate::allows('user-has-permission', Auth::user()->id))) {
            abort(
                401,
                'Action not allowed'
            );
        }
    }


    public function create(Request $request)
    { // owner, date, amount
        
        $this->validate(
            $request,
            [
                'name'     => 'required|string',
                'email'    => ['required', 'string', 'email'],
                'password' => 'required|string',
                'role'     => ['required', 'string', 'regex:#^(customer|admin)$#'],
            ]
        );

        return $this->userCreate($request->role, $request->name, $request->email, $request->password);
    }

    public function userCreate($role, $name, $email, $password)
    {
        $rolesID = Roles::select('id')->where('name', '=', $role)->get()->toArray();

        if (empty($rolesID)) {
            abort(
                400,
                'Role not found'
            );
        }

        $aux = User::where(
            'email', '=',  $email
        )->get()
        ->toArray();

        if ($aux) {
            abort(
                400,
                'Email found in database'
            );
        }

        $userId = User::create(
            [
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password)
            ]
        );

        if ($userId) {
            Permissions::insert(
                ['users_id' => $userId->id, 'roles_id' => $rolesID[0] ['id']]
            );

            return [
                'status' => 'success',
                'data' => [
                    'user_id' => $userId->id
                ]
            ];
        }

    }

    public function view()
    {

        $users = User::paginate(10);

        return $users;
    }

    //
}
