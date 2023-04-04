<?php

namespace Modules\User\Http\Controllers\Api;

use Exception;
use Modules\User\LoginProvider;
use Modules\User\Entities\User;
use Laravel\Socialite\Facades\Socialite;
use Modules\User\Events\CustomerRegistered;
use Modules\User\Http\Requests\LoginRequest;
use Modules\User\Transformers\UserTransformer;
use Modules\User\Http\Controllers\BaseAuthController;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use Cartalyst\Sentinel\Checkpoints\NotActivatedException;

class AuthController extends BaseAuthController
{
    /**
     * Where to redirect users after login..
     *
     * @return string
     */
    protected function redirectTo()
    {
        return route('api.user.authenticated');
    }

    /**
     * The login URL.
     *
     * @return string
     */
    protected function loginUrl()
    {
        return route('api.login');
    }

    /**
     * Show login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogin()
    {
        return route('api.login');
    }

    /**
     * Login a user.
     *
     * @param \Modules\User\Http\Requests\LoginRequest $request
     * @return \Illuminate\Http\Response
     */
    public function postLogin(LoginRequest $request)
    {
        try {
            $loggedIn = $this->auth->login([
                'email' => $request->email,
                'password' => $request->password,
            ], (bool) $request->get('remember_me', false));

            abort_if(! $loggedIn, 401, trans('user::messages.users.invalid_credentials'));

            return redirect()->intended($this->redirectTo());
        } catch (NotActivatedException $e) {
            abort(403, trans('user::messages.users.account_not_activated'));
        } catch (ThrottlingException $e) {
            abort(403, trans('user::messages.users.account_is_blocked', ['delay' => $e->getDelay()]));
        }
    }

    /**
     * Redirect the user to the given provider authentication page.
     *
     * @param string $provider
     * @return \Illuminate\Http\JsonResponse
     */
    public function redirectToProvider($provider)
    {
        if (! LoginProvider::isEnable($provider)) {
            abort(404);
        }
        
        return Socialite::driver($provider)->stateless()->redirect();
    }

    /**
     * Obtain the user information from the given provider.
     *
     * @param string $provider
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleProviderCallback($provider)
    {
        if (! LoginProvider::isEnable($provider)) {
            abort(404);
        }

        try {
            $user = Socialite::driver($provider)->stateless()->user();
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }

        if (User::registered($user->getEmail())) {
            $user = User::findByEmail($user->getEmail());
            auth()->login($user);
            auth()->user()->update([ 'last_login' => now() ]);

            return new UserTransformer($user);
        }

        $user = User::create([
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'phone' => '',
            'password' => str_random(),
            'email_verified_at' => now(),
            'last_login' => now()
        ]);

        $this->assignCustomerRole($user);
        auth()->login($user);

        event(new CustomerRegistered($user));

        return new UserTransformer($user);
    }

    /**
     * Show reset password form.
     *
     * @return \Illuminate\Http\Response
     */
    public function getReset()
    {
        return view('user::admin.auth.reset.begin');
    }

    /**
     * Reset complete form route.
     *
     * @param \Modules\User\Entities\User $user
     * @param string $code
     * @return string
     */
    protected function resetCompleteRoute($user, $code)
    {
        return route('admin.reset.complete', [$user->email, $code]);
    }

    /**
     * Password reset complete view.
     *
     * @return string
     */
    protected function resetCompleteView()
    {
        return view('user::admin.auth.reset.complete');
    }
}
