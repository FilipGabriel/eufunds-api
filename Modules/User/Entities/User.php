<?php

namespace Modules\User\Entities;

use Modules\Media\Entities\File;
use Modules\User\Admin\UserTable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Mail;
use Modules\Media\Eloquent\HasMedia;
use Modules\Support\Search\Searchable;
use Modules\User\Repositories\Permission;
use Modules\User\Mail\ResetPasswordEmail;
use Modules\User\Mail\ActivationCodeEmail;
use Lab404\Impersonate\Models\Impersonate;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Cartalyst\Sentinel\Permissions\PermissibleTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Cartalyst\Sentinel\Permissions\StandardPermissions;
use Cartalyst\Sentinel\Permissions\PermissionsInterface;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable implements MustVerifyEmail
{
    use PermissibleTrait,
        HasApiTokens,
        Impersonate,
        HasMedia,
        CanResetPassword,
        Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'phone', 'about', 'password', 'permissions', 'email_verified_at', 'last_login', 'remember_token'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['last_login'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'user_logo',
        'user_email',
    ];

    /**
     * @return bool
     */
    public function canImpersonate()
    {
        return $this->hasRoleName('Admin');
    }

    /**
     * @return bool
     */
    public function canBeImpersonated()
    {
        return $this->hasRoleName('Customer') && $this->id !== auth()->user()->id;
    }

    public function sendEmailVerificationNotification()
    {
        Mail::to($this)->send(new ActivationCodeEmail($this));
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        Mail::to($this)->send(new ResetPasswordEmail($this, $token));
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

    public function scopeWithLogo($query)
    {
        $query->with(['files' => function ($q) {
            $q->wherePivot('zone', 'user_logo');
        }]);
    }

    /**
     * Get the user logo.
     *
     * @return \Modules\Media\Entities\File
     */
    public function getUserLogoAttribute()
    {
        return $this->files->where('pivot.zone', 'user_logo')->first() ?: new File;
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

    public function hasVerifiedEmail()
    {
        return ! is_null($this->email_verified_at);
    }

    /**
     * Check if the current user is activated.
     *
     * @return bool
     */
    public function isActivated()
    {
        return ! is_null($this->email_verified_at);
    }

    /**
     * Check if the current user is not activated.
     *
     * @return bool
     */
    public function isNotActivated()
    {
        return ! $this->isActivated();
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
     * Get a list of all customers.
     *
     * @return array
     */
    public static function customerList()
    {
        return static::get()->pluck('name', 'id');
    }

    public static function totalCustomers()
    {
        return Role::findOrNew(setting('customer_role'))->users()->count();
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
     * Creates a permissions object.
     *
     * @return \Cartalyst\Sentinel\Permissions\PermissionsInterface
     */
    protected function createPermissions(): PermissionsInterface
    {
        $userPermissions = json_decode($this->permissions, true) ?: [];

        $rolePermissions = [];

        foreach ($this->roles as $role) {
            $rolePermissions[] = $role->permissions ?? [];
        }

        return new StandardPermissions($userPermissions, $rolePermissions);
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

    public function forCard()
    {
        return [
            'name' => $this->name,
            'user_logo' => [
                'path' => $this->user_logo->path,
                'exists' => $this->user_logo->exists,
            ],
        ];
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

    public function searchTable()
    {
        return 'users';
    }

    public function searchKey()
    {
        return 'id';
    }

    public function searchColumns()
    {
        return ['name'];
    }

    public function getUserEmailAttribute()
    {
        return "{$this->name} ({$this->email})";
    }
}
