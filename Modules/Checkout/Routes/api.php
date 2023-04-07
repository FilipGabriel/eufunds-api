<?php

use Illuminate\Support\Facades\Route;

Route::post('checkout', 'CheckoutController@store')->name('checkout.store');

