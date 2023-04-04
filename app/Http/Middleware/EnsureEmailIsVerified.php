<?php

namespace Smis\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class EnsureEmailIsVerified
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'api.login.*',
        'password.email',
        'verification.verify',
        'api.users.show'
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $redirectToRoute
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|null
     */
    public function handle($request, Closure $next, $redirectToRoute = null)
    {
        if ($this->inExceptArray($request)) {
            return $next($request);
        }

        if (
            ! auth()->user() ||
            (auth()->user() instanceof MustVerifyEmail &&
            ! auth()->user()->hasVerifiedEmail() && ! auth()->user()->isImpersonated())
        ) {
            $shouldResendEmail = $this->shouldResendEmail();

            if($shouldResendEmail) {
                auth()->user()->touch();
                auth()->user()->sendEmailVerificationNotification();
            }

            return $request->expectsJson()
                    ? response()->json([
                        'message' => trans('core::messages.email_not_verified'),
                        'resent' => $shouldResendEmail
                    ], 403) : abort(403, trans('core::messages.email_not_verified'));
        }

        return $next($request);
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

    private function shouldResendEmail()
    {
        if(! auth()->user() || auth()->user()->isImpersonated() || auth()->user()->hasVerifiedEmail()) {
            return false;
        }

        return now()->diffInHours(auth()->user()->updated_at) > 0;
    }
}
