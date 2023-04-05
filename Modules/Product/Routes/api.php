<?php

use Illuminate\Support\Facades\Route;

Route::get('suggestions', 'SuggestionController@index')->name('suggestions.index');
