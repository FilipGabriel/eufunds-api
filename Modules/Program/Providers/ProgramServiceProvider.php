<?php

namespace Modules\Program\Providers;

use Modules\Support\Traits\AddsAsset;
use Illuminate\Support\ServiceProvider;

class ProgramServiceProvider extends ServiceProvider
{
    use AddsAsset;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->addAdminAssets('admin.programs.index', [
            'admin.program.css', 'admin.jstree.js', 'admin.program.js',
            'admin.media.css', 'admin.media.js',
        ]);
    }
}
