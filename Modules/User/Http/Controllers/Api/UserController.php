<?php

namespace Modules\User\Http\Controllers\Api;

use Modules\User\Entities\User;
use Illuminate\Routing\Controller;
use Modules\User\Http\Requests\TokenRequest;
use Modules\User\Transformers\UserTransformer;
use Modules\User\Http\Middleware\AuthorizeRequest;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;

class UserController extends Controller
{
    /**
     * Model for the resource.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Label of the resource.
     *
     * @var string
     */
    protected $label = 'user::users.user';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(AuthorizeRequest::class)->only(['generateToken']);
    }

    /**
     * Get an instance of the currently authenticated user
     */
    public function getAuthenticated()
    {
        auth()->user()->update([ 'last_login' => now() ]);

        return new UserTransformer(auth()->user());
    }

    /**
     * Create user and generate token.
     */
    public function generateToken(TokenRequest $request)
    {
        try {
            $updatedBy = User::registered($request->email) ? ['email' => $request->email] : ['nod_id' => $request->nod_id];
            $user = User::updateOrCreate($updatedBy, $request->validated());
            $user->tokens()->delete();

            if($user->roles->isEmpty()) {
                $user->roles()->sync([setting('customer_role')]);
            }

            return $user->createToken('bearer')->plainTextToken;
        } catch (ThrottlingException $e) {
            abort(403, trans('user::messages.users.account_is_blocked', ['delay' => $e->getDelay()]));
        }
    }
}
