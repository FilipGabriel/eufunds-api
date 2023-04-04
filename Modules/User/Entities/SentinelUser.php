<?php

namespace Modules\User\Entities;

use Modules\User\Admin\UserTable;
use Illuminate\Auth\Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Modules\User\Entities\UserToken;
use Illuminate\Notifications\Notifiable;
use Modules\User\Repositories\Permission;
use Cartalyst\Sentinel\Users\EloquentUser;
use Lab404\Impersonate\Models\Impersonate;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class SentinelUser extends EloquentUser implements AuthenticatableContract
{
    use Authenticatable,
        HasApiTokens,
        Impersonate,
        Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'phone',
        'password',
        'name',
        'permissions',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['last_login'];

    public function markEmailAsVerified()
    {
        Activation::complete($this);
    }

    public function getEmailForVerification()
    {
        return $this->email;
    }

    public static function registered($email)
    {
        return static::where('email', $email)->exists();
    }

    public static function findByEmail($email)
    {
        return static::where('email', $email)->first();
    }

    public static function totalCustomers()
    {
        return Role::findOrNew(setting('customer_role'))->users()->count();
    }

    /**
     * Login the user.
     *
     * @return $this|bool
     */
    public function login()
    {
        return auth()->login($this);
    }

    /**
     * Determine if the user is a customer.
     *
     * @return bool
     */
    public function isCustomer()
    {
        if ($this->hasRoleName('admin')) {
            return false;
        }

        return $this->hasRoleId(setting('customer_role'));
    }

    /**
     * Checks if a user belongs to the given Role ID.
     *
     * @param int $roleId
     * @return bool
     */
    public function hasRoleId($roleId)
    {
        return $this->roles()->whereId($roleId)->count() !== 0;
    }

    /**
     * Checks if a user belongs to the given Role Name.
     *
     * @param string $name
     * @return bool
     */
    public function hasRoleName($name)
    {
        return $this->roles()->whereTranslation('name', $name)->count() !== 0;
    }

    /**
     * Check if the current user is activated.
     *
     * @return bool
     */
    public function hasVerifiedEmail()
    {
        return is_integer($this->getKey()) && Activation::completed($this);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function api_keys()
    {
        return $this->hasMany(UserToken::class);
    }

    /**
     * @inheritdoc
     */
    public function getFirstApiKey()
    {
        $userToken = $this->api_keys->first();

        if ($userToken === null) {
            return '';
        }

        return $userToken->access_token;
    }

    /**
     * Get the roles of the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles')->withTimestamps();
    }

    /**
     * Set user's permissions.
     *
     * @param array $permissions
     * @return void
     */
    public function setPermissionsAttribute(array $permissions)
    {
        $this->attributes['permissions'] = Permission::prepare($permissions);
    }

    /**
     * Determine if the user has access to the given permissions.
     *
     * @param array|string $permissions
     * @return bool
     */
    public function hasAccess($permissions)
    {
        $permissions = is_array($permissions) ? $permissions : func_get_args();

        return $this->getPermissionsInstance()->hasAccess($permissions);
    }

    /**
     * Determine if the user has access to the any given permissions
     *
     * @param array|string $permissions
     * @return bool
     */
    public function hasAnyAccess($permissions)
    {
        $permissions = is_array($permissions) ? $permissions : func_get_args();

        return $this->getPermissionsInstance()->hasAnyAccess($permissions);
    }

    /**
     * Get table data for the resource
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function table()
    {
        return new UserTable($this->newQuery());
    }
}
