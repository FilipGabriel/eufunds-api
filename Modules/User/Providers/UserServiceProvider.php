<?php

namespace Modules\User\Providers;

use Modules\User\Admin\RoleTabs;
use Modules\User\Admin\UserTabs;
use Modules\User\Guards\Fortify;
use Modules\User\Admin\ProfileTabs;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use Modules\Support\Traits\AddsAsset;
use Illuminate\Support\ServiceProvider;
use Modules\Admin\Ui\Facades\TabManager;
use Illuminate\Auth\EloquentUserProvider;
use Modules\User\Contracts\Authentication;
use Modules\User\Fortify\FortifyAuthentication;
use Modules\Admin\Http\ViewComposers\AssetsComposer;
use Modules\User\Http\ViewComposers\CurrentUserComposer;

class UserServiceProvider extends ServiceProvider
{
    use AddsAsset;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (! config('app.installed')) {
            return;
        }

        TabManager::register('users', UserTabs::class);
        TabManager::register('roles', RoleTabs::class);
        TabManager::register('profile', ProfileTabs::class);

        View::composer('*', CurrentUserComposer::class);
        View::composer('user::admin.auth.layout', AssetsComposer::class);

        $this->addAdminAssets('admin.(login|reset).*', ['admin.login.css', 'admin.login.js']);
        $this->addAdminAssets('admin.(users|roles).(create|edit)', ['admin.user.css', 'admin.user.js', 'admin.media.css', 'admin.media.js']);
        
        $this->registerSentinelGuard();
        $this->registerBladeDirectives();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Authentication::class, FortifyAuthentication::class);
    }

    /**
     * Register sentinel guard.
     *
     * @return void
     */
    private function registerSentinelGuard()
    {
        Auth::extend(
            'fortify',
            function ($app) {
                $provider = new EloquentUserProvider($app['hash'], config('auth.providers.users.model'));
                $guard =  new Fortify('fortify', $provider, app()->make('session.store'), request());
                if (method_exists($guard, 'setCookieJar')) {
                    $guard->setCookieJar($this->app['cookie']);
                }
                return $guard;
            }
        );
    }

    /**
     * Register blade directives.
     *
     * @return void
     */
    private function registerBladeDirectives()
    {
        Blade::directive('hasAccess', function ($permissions) {
            return "<?php if (\$currentUser->hasAccess($permissions)) : ?>";
        });

        Blade::directive('endHasAccess', function () {
            return '<?php endif; ?>';
        });

        Blade::directive('hasAnyAccess', function ($permissions) {
            return "<?php if (\$currentUser->hasAnyAccess($permissions)) : ?>";
        });

        Blade::directive('endHasAnyAccess', function () {
            return '<?php endif; ?>';
        });
    }
}
