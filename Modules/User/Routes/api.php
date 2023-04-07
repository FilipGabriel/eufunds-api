<?php

use Illuminate\Support\Facades\Route;

Route::get('login/{provider}', 'AuthController@redirectToProvider')->name('api.login.redirect');
Route::get('login/{provider}/callback', 'AuthController@handleProviderCallback')->name('api.login.callback');

Route::get('account/orders', 'UserController@index')->name('account.orders.index');
Route::get('account/orders/{id}', 'UserController@show')->name('account.orders.show');

Route::get('user', [
    'as' => 'api.user.authenticated',
    'uses' => 'UserController@getAuthenticated',
]);
