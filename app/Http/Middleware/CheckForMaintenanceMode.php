<?php

namespace Smis\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode as BaseCheckForMaintenanceMode;

class CheckForMaintenanceMode extends BaseCheckForMaintenanceMode
{
    /**
     * The URIs that should be accessible while maintenance mode is enabled.
     *
     * @var array
     */
    protected $except = [
        '*/admin*',
        '*/api/v1/primary-landing-menu',
        '*/api/v1/categories',
        '*/api/v1/news*',
        '*/api/v1/landing/news*',
        '*/api/v1/m3-manager/consultant'
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @throws \Illuminate\Foundation\Http\Exceptions\MaintenanceModeException
     */
    public function handle($request, Closure $next)
    {
        if (
            config('app.installed')
            && $this->app->isDownForMaintenance()
            && optional(auth()->user())->hasRoleName('Admin')
        ) {
            return $next($request);
        }

        return parent::handle($request, $next);
    }
}
