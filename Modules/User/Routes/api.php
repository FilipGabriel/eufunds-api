<?php

use Illuminate\Support\Facades\Route;

Route::post('tokens', 'UserController@generateToken')->name('api.user.tokens');
Route::get('user', 'UserController@getAuthenticated')->name('api.user.authenticated');