<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('program/downloads/{id}', 'ProgramDownloadsController@show')->name('program.downloads.show');
});
