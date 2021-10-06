<?php 
namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class UserPolicy
{
    /**
     * Determine if the given action can be used by the user.
     * 
     * Admin - Can do all actions on customers accounts
     * Customer - Can do all action in your own account
     *
     * @param  \App\Models\User  $user
     * @param  integer $userIdFromAction - user id sent in the request
     * @return bool
     */
    public function permission($user, $userIdInRequest)
    {
        if ($this->userIsAdmin($user)) {
            return true;
        }

        if ($this->userInResquestIsTheSameLoggedIn($user, $userIdInRequest)) {
            return true;
        }

        return false;
    }

    public function userIsAdmin($user)
    {
        return ($user->fetchRole()) ['name'] == 'admin';
    }

    public function userInResquestIsTheSameLoggedIn($user, $userIdInRequest)
    {
        return $user->id == $userIdInRequest;
    }

}