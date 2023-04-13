<?php

namespace Smis\Http\Middleware;

use Closure;

class RedirectIfInstalled
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (config('app.installed')) {
            return redirect()->route('admin.dashboard.index');
        }

        return $next($request);
    }
}
