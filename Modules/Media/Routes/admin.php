<?php

use Illuminate\Support\Facades\Route;

Route::get('ftp', [
    'as' => 'admin.ftp.index',
    'uses' => 'FtpController@index',
    'middleware' => 'can:admin.media.index',
]);

Route::delete('ftp/{ids?}', [
    'as' => 'admin.ftp.destroy',
    'uses' => 'FtpController@destroy',
    'middleware' => 'can:admin.media.destroy',
]);

Route::get('media', [
    'as' => 'admin.media.index',
    'uses' => 'MediaController@index',
    'middleware' => 'can:admin.media.index',
]);

Route::post('media', [
    'as' => 'admin.media.store',
    'uses' => 'MediaController@store',
    'middleware' => 'can:admin.media.create',
]);

Route::delete('media/{ids?}', [
    'as' => 'admin.media.destroy',
    'uses' => 'MediaController@destroy',
    'middleware' => 'can:admin.media.destroy',
]);

Route::get('file-manager', [
    'uses' => 'FileManagerController@index',
    'as' => 'admin.file_manager.index',
    'middleware' => 'can:admin.media.index',
]);
