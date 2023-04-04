<?php

namespace Themes\Appfront\Providers;

use Modules\Support\Traits\AddsAsset;
use Illuminate\Support\ServiceProvider;
use Modules\Admin\Ui\Facades\TabManager;
use Themes\Appfront\Admin\AppfrontTabs;

class AppfrontServiceProvider extends ServiceProvider
{
    use AddsAsset;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        TabManager::register('appfront', AppfrontTabs::class);

        $this->addAdminAssets('admin.appfront.settings.edit', [
            'admin.appfront.css', 'admin.media.css', 'admin.appfront.js', 'admin.media.js',
        ]);
    }
}
