<?php

namespace Modules\User\Services;

use Modules\User\Entities\Role;
use Modules\Media\Entities\File;
use Modules\User\Entities\Profile;
use Illuminate\Support\Facades\Storage;
use Modules\User\Contracts\Authentication;

class CustomerService
{
    private $auth;

    public function __construct(Authentication $auth)
    {
        $this->auth = $auth;
    }

    public function register($request)
    {
        return tap($this->auth->registerAndActivate($this->getCustomerData($request)), function ($user) {
            $role = Role::find(setting('customer_role'));

            $user->roles()->attach($role);
        });
    }

    private function getCustomerData($request)
    {
        return [
            'email' => $request->customer_email,
            'phone' => $request->customer_phone,
            'password' => $request->password,
        ];
    }

    public function handleProfileImage($request)
    {
        $image = auth()->user()->user_logo;
        $params = [
            'user_id' => auth()->id(),
            'location' => 'users',
            'file' => $request->file('image')
        ];

        if($image->exists) {
            Storage::disk($image->disk)->delete($image->getRawOriginal('path'));
            $image->updateFile($params);
        } else {
            File::uploadFile($params, auth()->user(), 'user_logo');
        }
    }

    public function saveProfile($request)
    {
        $profile = new Profile([]);

        return auth()->user()->profile()->updateOrCreate(
            [ 'user_id' => auth()->id() ],
            $request->only($profile->getFillable())
        );
    }
}
