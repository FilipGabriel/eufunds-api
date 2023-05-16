<?php

use Illuminate\Support\Facades\Route;

Route::get('currency-rates', 'CurrencyRateController@index')->name('currency_rates.index');
Route::get('current-currency/{code}', 'CurrentCurrencyController@store')->name('current_currency.store');
