<?php

namespace Modules\User\Contracts;

use Modules\User\Entities\Role;
use Modules\User\Entities\User;

interface Authentication
{
    /**
     * Authenticate a user.
     *
     * @param array $credentials
     * @param bool $remember
     * @return mixed
     */
    public function login($credentials, $remember = false);

    /**
     * Assign a role to the given user.
     *
     * @param \Modules\User\Entities\User $user
     * @param \Modules\User\Entities\Role $role
     * @return void
     */
    public function assignRole(User $user, Role $role);

    /**
     * Log the user out of the application.
     *
     * @return bool
     */
    public function logout();

    /**
     * Determines if the current user has access to the given permissions.
     *
     * @param array|string $permissions
     * @return bool
     */
    public function hasAccess($permissions);

    /**
     * Determine if the user has access to the any given permissions
     *
     * @param array|string $permissions
     * @return bool
     */
    public function hasAnyAccess($permissions);

    /**
     * Check if the user is logged in.
     *
     * @return bool
     */
    public function check();

    /**
     * Get the currently logged in user.
     *
     * @return \Modules\User\Entities\User
     */
    public function user();

    /**
     * Get the ID for the currently authenticated user.
     *
     * @return int|null
     */
    public function id();
}