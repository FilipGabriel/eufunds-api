<?php

namespace Modules\User\Entities;

use Modules\Admin\Ui\AdminTable;
use Modules\User\Repositories\Permission;
use Cartalyst\Sentinel\Roles\EloquentRole;
use Modules\Support\Eloquent\Translatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends EloquentRole
{
    use Translatable;

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['translations'];

    /**
     * The attributes that are translatable.
     *
     * @var array
     */
    protected $translatedAttributes = ['name'];

    /**
     * Get a list of all roles.
     *
     * @return array
     */
    public static function list()
    {
        return static::select('id')->get()->pluck('name', 'id');
    }

    /**
     * Get a list of all roles.
     *
     * @return array
     */
    public static function affiliateList()
    {
        return self::whereTranslation('name', 'affiliate')->first()->users->pluck('name', 'id');
    }

    /**
     * Get a list of all roles.
     *
     * @return array
     */
    public static function usersForTasks()
    {
        return self::whereTranslationIn('name', [
            'admin', 'dev', 'developer', 'marketing', 'm3 consultant'
        ])->with('users')->get()->pluck('users')->flatten()->pluck('id')->toArray();
    }

    /**
     * The Users relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_roles', 'role_id', 'user_id')->withTimestamps();
    }

    /**
     * Set role's permissions.
     *
     * @param array $permissions
     * @return void
     */
    public function setPermissionsAttribute(array $permissions)
    {
        $this->attributes['permissions'] = Permission::prepare($permissions);
    }

    /**
     * Get table data for the resource
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function table()
    {
        return new AdminTable($this->newQuery());
    }
}
