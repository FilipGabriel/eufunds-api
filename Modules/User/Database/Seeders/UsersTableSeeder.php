<?php

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\User\Entities\Role;
use Modules\User\Entities\User;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = DB::table('old_users')
            ->select([
                'old_users.name',
                'old_users.email',
                'old_users.password',
                'old_users.phone'
            ])
            ->get();

        $users->map(function($user) {

            $role = Role::find(setting('customer_role'));

            if(User::whereEmail($user->email)->exists()) {
                $newUser = User::whereEmail($user->email)->first();
            } else {
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'password' => $user->password,
                    'phone' => $user->phone ?? ''
                ]);
            }

            if(! $newUser->hasRoleName($role->name)) {
                $newUser->roles()->attach($role);
            }
        });
    }
}
