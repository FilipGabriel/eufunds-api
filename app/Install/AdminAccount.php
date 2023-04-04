<?php

namespace Smis\Install;

use Modules\User\Entities\Role;
use Modules\User\Entities\User;

class AdminAccount
{
    public function setup($data)
    {
        $role = Role::create(['name' => 'Admin', 'permissions' => $this->getAdminRolePermissions()]);

        $admin = User::create([
            'name' => $data['first_name'] . ' ' . $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => bcrypt($data['password']),
            'email_verified_at' => now(),
        ]);

        $admin->roles()->attach($role);
    }

    private function getAdminRolePermissions()
    {
        return [
            // users
            'admin.users.index' => true,
            'admin.users.create' => true,
            'admin.users.edit' => true,
            'admin.users.destroy' => true,
            // roles
            'admin.roles.index' => true,
            'admin.roles.create' => true,
            'admin.roles.edit' => true,
            'admin.roles.destroy' => true,
            // products
            'admin.products.index' => true,
            'admin.products.create' => true,
            'admin.products.edit' => true,
            'admin.products.destroy' => true,
            // brands
            'admin.brands.index' => true,
            'admin.brands.create' => true,
            'admin.brands.edit' => true,
            'admin.brands.destroy' => true,
            // programs
            'admin.programs.index' => true,
            'admin.programs.create' => true,
            'admin.programs.edit' => true,
            'admin.programs.destroy' => true,
            // attributes
            'admin.attributes.index' => true,
            'admin.attributes.create' => true,
            'admin.attributes.edit' => true,
            'admin.attributes.destroy' => true,
            // attribute sets
            'admin.attribute_sets.index' => true,
            'admin.attribute_sets.create' => true,
            'admin.attribute_sets.edit' => true,
            'admin.attribute_sets.destroy' => true,
            // options
            'admin.options.index' => true,
            'admin.options.create' => true,
            'admin.options.edit' => true,
            'admin.options.destroy' => true,
            // filters
            'admin.filters.index' => true,
            'admin.filters.create' => true,
            'admin.filters.edit' => true,
            'admin.filters.destroy' => true,
            // categories
            'admin.categories.index' => true,
            'admin.categories.create' => true,
            'admin.categories.edit' => true,
            'admin.categories.destroy' => true,
            // Media
            'admin.media.index' => true,
            'admin.media.create' => true,
            'admin.media.destroy' => true,
            // currency rates
            'admin.currency_rates.index' => true,
            'admin.currency_rates.edit' => true,
            // translations
            'admin.translations.index' => true,
            'admin.translations.edit' => true,
            // coupons
            'admin.coupons.index' => true,
            'admin.coupons.create' => true,
            'admin.coupons.edit' => true,
            'admin.coupons.destroy' => true,
            // settings
            'admin.settings.edit' => true,
            // appfront
            'admin.appfront.edit' => true,
        ];
    }
}
