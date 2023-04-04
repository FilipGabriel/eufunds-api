<?php

use Illuminate\Support\Facades\Route;

Route::get('appfront', [
    'as' => 'admin.appfront.settings.edit',
    'uses' => 'AppfrontController@edit',
    'middleware' => 'can:admin.appfront.edit',
]);

Route::put('appfront', [
    'as' => 'admin.appfront.settings.update',
    'uses' => 'AppfrontController@update',
    'middleware' => 'can:admin.appfront.edit',
]);
