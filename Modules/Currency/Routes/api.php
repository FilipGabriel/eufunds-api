<?php

use Illuminate\Support\Facades\Route;

Route::get('currency-rates', 'CurrencyRateController@index')->name('currency_rates.index');
