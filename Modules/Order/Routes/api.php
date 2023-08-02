<?php

use Illuminate\Support\Facades\Route;

Route::get('account/orders', 'OrderController@index')->name('account.orders.index');
Route::get('account/orders/{id}', 'OrderController@show')->name('account.orders.show');
Route::get('account/orders/{id}/download', 'OrderController@download')->name('account.orders.download');
Route::get('account/orders/{id}/transform', 'OrderController@transform')->name('account.orders.transform');