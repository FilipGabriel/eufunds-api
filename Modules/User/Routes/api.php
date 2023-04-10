<?php

use Illuminate\Support\Facades\Route;

Route::get('login/{provider}', 'AuthController@redirectToProvider')->name('api.login.redirect');
Route::get('login/{provider}/callback', 'AuthController@handleProviderCallback')->name('api.login.callback');

Route::get('user', 'UserController@getAuthenticated')->name('api.user.authenticated');
