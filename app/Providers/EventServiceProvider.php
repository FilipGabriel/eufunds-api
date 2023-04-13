<?php

namespace Smis\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        // NewError::class => [
        //     SendErrorNotifications::class,
        // ],
    ];
}
