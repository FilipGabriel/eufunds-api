<?php

namespace Modules\User\Http\Middleware;

use Closure;

class AuthorizeRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        abort_if(
            $request->header('api-user') !== config('services.nod.user')
        , 403);

        return $next($request);
    }
}