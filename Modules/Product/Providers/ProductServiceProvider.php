<?php

namespace Modules\Product\Providers;

use Modules\Support\Traits\AddsAsset;
use Modules\Product\Admin\ProductTabs;
use Illuminate\Support\ServiceProvider;
use Modules\Admin\Ui\Facades\TabManager;

class ProductServiceProvider extends ServiceProvider
{
    use AddsAsset;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        TabManager::register('products', ProductTabs::class);

        $this->addAdminAssets('admin.products.(create|edit)', [
            'admin.media.css', 'admin.media.js', 'admin.product.css', 'admin.product.js',
        ]);

        $this->addAdminAssets('admin.products.index', ['admin.filter.js']);
    }
}
