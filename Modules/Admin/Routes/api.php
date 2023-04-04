<?php

use Illuminate\Support\Facades\Route;

Route::get('/app/dashboard', 'DashboardController@index')->name('api.app.dashboard.index');


