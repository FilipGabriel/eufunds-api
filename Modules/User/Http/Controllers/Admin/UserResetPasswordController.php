<?php

namespace Modules\User\Http\Controllers\Admin;

use Laravel\Fortify\Fortify;
use Modules\User\Entities\User;
use Illuminate\Support\Facades\Password;

class UserResetPasswordController
{
    /**
     * @param $id
     * @return mixed
     */
    public function store($id)
    {
        $user = User::findOrFail($id);
        $broker = Password::broker(config('fortify.passwords'));

        $emailField = Fortify::email();
        $broker->sendResetLink([$emailField => $user->$emailField]);

        return redirect()->route('admin.users.index')
            ->withSuccess(trans('user::messages.users.reset_password_email_sent'));
    }
}
