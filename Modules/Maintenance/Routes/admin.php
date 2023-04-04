<?php

use Illuminate\Support\Facades\Route;

Route::get('/php-info', [
    'as' => 'admin.maintenance.phpinfo',
    'uses' => 'MaintenanceController@phpinfo',
    'middleware' => 'can:admin.settings.edit',
]);

Route::get('/logs/{logFile?}', [
    'as' => 'admin.maintenance.logs',
    'uses' => 'MaintenanceController@logs',
    'middleware' => 'can:admin.settings.edit',
]);
