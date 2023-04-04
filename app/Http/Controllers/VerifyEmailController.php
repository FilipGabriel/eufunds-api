<?php

namespace Smis\Http\Controllers;

use Illuminate\Http\Request;
use Modules\User\Entities\User;
use Illuminate\Routing\Controller;
use Illuminate\Auth\Events\Verified;

class VerifyEmailController extends Controller
{
    public function verify(Request $request)
    {
        $user = User::find($request->route('id'));

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => trans('user::messages.users.account_already_activated')]);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return response()->json(['message' => trans('user::messages.users.account_activated')]);
    }
}
