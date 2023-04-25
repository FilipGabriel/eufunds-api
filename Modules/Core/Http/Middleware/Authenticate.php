<?php

namespace Modules\Core\Http\Middleware;

use Closure;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    /**
     * The routes that should be excluded from verification.
     *
     * @var array
     */
    protected $except = [
        'api.user.tokens',
        'programs.index'
    ];

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return \Illuminate\Http\Response
     */
    public function handle($request, Closure $next)
    {
        if ($this->inExceptArray($request)) { return $next($request); }

        $token = $request->bearerToken();
        $model = Sanctum::$personalAccessTokenModel;

        if($token && $accessToken = $model::findToken($token)) {
            if (! $this->isValidAccessToken($accessToken)) {
                abort(419, trans('user::messages.users.unauthenticated'));
            }

            Auth::loginUsingId($accessToken->tokenable_id);
            $accessToken->forceFill(['last_used_at' => now()])->save();
        }

        if (auth()->check()) { return $next($request); }

        abort(419, trans('user::messages.users.unauthenticated'));
    }

    /**
     * Determine if the request URI is in except array.
     *
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    protected function inExceptArray($request)
    {
        foreach ($this->except as $except) {
            $routeName = optional($request->route())->getName();

            if (preg_match("/{$except}/", $routeName)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if the provided access token is valid.
     *
     * @param  mixed  $accessToken
     * @return bool
     */
    protected function isValidAccessToken($accessToken): bool
    {
        if (! $accessToken) {
            return false;
        }

        $expiration = config('sanctum.expiration');

        $isValid =
            (! $expiration || $accessToken->created_at->gt(now()->subMinutes($expiration)))
            && $this->hasValidProvider($accessToken->tokenable);

        if (is_callable(Sanctum::$accessTokenAuthenticationCallback)) {
            $isValid = (bool) (Sanctum::$accessTokenAuthenticationCallback)($accessToken, $isValid);
        }
        
        return $isValid;
    }

    /**
     * Determine if the tokenable model matches the provider's model type.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $tokenable
     * @return bool
     */
    protected function hasValidProvider($tokenable)
    {
        $model = config("auth.providers.users.model");

        return $tokenable instanceof $model;
    }
}
