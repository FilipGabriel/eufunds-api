<?php

use Illuminate\Support\Facades\Route;

Route::get('brands/{brand}/products', 'BrandProductController@index')->name('brands.products.index');
