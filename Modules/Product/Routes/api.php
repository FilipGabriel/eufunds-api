<?php

use Illuminate\Support\Facades\Route;

Route::get('products/{slug}', 'ProductController@show')->name('products.show');

Route::get('suggestions', 'SuggestionController@index')->name('suggestions.index');
