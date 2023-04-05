<?php

use Illuminate\Support\Facades\Route;

Route::get('programs', 'ProgramController@index')->name('programs.index');

Route::get('programs/{program}/products', 'ProgramProductController@index')->name('programs.products.index');
Route::get('programs/{program}/products/{slug}', 'ProgramProductController@show')->name('programs.products.show');