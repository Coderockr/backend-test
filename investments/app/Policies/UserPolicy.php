<?php 
namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class UserPolicy
{
    /**
     * Determine if the given post can be updated by the user.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function show($user, $var)
    {
        dd(
            $var,
            $user->id,
            $user->fetchRole()
        );
        // return true;
    }
}