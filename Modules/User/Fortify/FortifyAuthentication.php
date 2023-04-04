<?php

namespace Modules\User\Fortify;

use Modules\User\Entities\Role;
use Modules\User\Entities\User;
use Illuminate\Support\Facades\Auth;
use Modules\User\Contracts\Authentication;

class FortifyAuthentication implements Authentication
{
    /**
     * Authenticate a user.
     *
     * @param array $credentials
     * @param bool $remember
     * @return mixed
     */
    public function login($credentials, $remember = false)
    {
        return Auth::attempt($credentials, $remember);
    }

    /**
     * Assign a role to the given user.
     *
     * @param \Modules\User\Entities\User $user
     * @param \Modules\User\Entities\Role $role
     * @return void
     */
    public function assignRole(User $user, Role $role)
    {
        $role->users()->attach($user);
    }

    /**
     * Log the user out of the application.
     *
     * @return bool
     */
    public function logout()
    {
        return Auth::logout();
    }

    /**
     * Determines if the current user has access to the given permissions.
     *
     * @param array|string $permissions
     * @return bool
     */
    public function hasAccess($permissions)
    {
        if (Auth::guest()) {
            return false;
        }

        $permissions = is_array($permissions) ? $permissions : func_get_args();

        return Auth::hasAccess($permissions);
    }

    /**
     * Determine if the current user has access to the any given permissions
     *
     * @param array|string $permissions
     * @return bool
     */
    public function hasAnyAccess($permissions)
    {
        if (Auth::guest()) {
            return false;
        }

        $permissions = is_array($permissions) ? $permissions : func_get_args();

        return Auth::hasAnyAccess($permissions);
    }

    /**
     * Check if the user is logged in.
     *
     * @return bool
     */
    public function check()
    {
        return Auth::check();
    }

    /**
     * Get the currently logged in user.
     *
     * @return \Modules\User\Entities\User|null
     */
    public function user()
    {
        return Auth::user();
    }

    /**
     * Get the ID for the currently authenticated user.
     *
     * @return int|null
     */
    public function id()
    {
        return optional($this->user())->id;
    }
}