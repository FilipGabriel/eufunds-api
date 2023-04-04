<?php

namespace Modules\User\Guards;

use Illuminate\Support\Str;
use Illuminate\Auth\SessionGuard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Authenticatable;

class Fortify extends SessionGuard
{
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

        $prepared = array_merge_recursive(... Auth::user()->roles()->pluck('permissions')->filter(function ($item) {
            return ! is_null($item) && ! empty($item);
        })->toArray());

        $prepared = array_replace_recursive($prepared, json_decode(Auth::user()->permissions, true) ?: []);

        foreach ($permissions as $permission) {
            if (! $this->checkPermission($prepared, $permission)) {
                return false;
            }
        }

        return true;
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

        $prepared = array_merge_recursive(... Auth::user()->roles()->pluck('permissions')->filter(function ($item) {
            return ! is_null($item) && ! empty($item);
        })->toArray());

        $prepared = array_replace_recursive($prepared, json_decode(Auth::user()->permissions, true) ?: []);

        foreach ($permissions as $permission) {
            if ($this->checkPermission($prepared, $permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks a permission in the prepared array, including wildcard checks and permissions.
     *
     * @param array  $prepared
     * @param string $permission
     *
     * @return bool
     */
    protected function checkPermission(array $prepared, string $permission): bool
    {
        if (array_key_exists($permission, $prepared)) {
            return $prepared[$permission] === true;
        }

        foreach ($prepared as $key => $value) {
            $key = (string) $key;

            if ((Str::is($permission, $key) || Str::is($key, $permission)) && $value === true) {
                return true;
            }
        }

        return false;
    }

    /**
     * Log a user into the application without firing the Login event.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return void
     */
    public function quietLogin(Authenticatable $user)
    {
        $this->updateSession($user->getAuthIdentifier());

        $this->setUser($user);
    }

    /**
     * Logout the user without updating remember_token
     * and without firing the Logout event.
     *
     * @param   void
     * @return  void
     */
    public function quietLogout()
    {
        $this->clearUserDataFromStorage();

        $this->user = null;

        $this->loggedOut = true;
    }
}