<?php

if (! function_exists('permission_value')) {
    /**
     * Get the integer representation value of the permission.
     *
     * @param array $permissions
     * @param string $permission
     * @return int
     */
    function permission_value($permissions, $permission)
    {
        $permissions = is_array($permissions) ? $permissions : json_decode($permissions, true);
        $value = array_get($permissions, $permission);

        if (is_null($value)) {
            return 0;
        } elseif ($value) {
            return 1;
        } elseif (! $value) {
            return -1;
        }
    }
}
