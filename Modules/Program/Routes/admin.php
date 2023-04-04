<?php

use Illuminate\Support\Facades\Route;

Route::get('programs/tree', [
    'as' => 'admin.programs.tree',
    'uses' => 'ProgramTreeController@index',
    'middleware' => 'can:admin.programs.index',
]);

Route::put('programs/tree', [
    'as' => 'admin.programs.tree.update',
    'uses' => 'ProgramTreeController@update',
    'middleware' => 'can:admin.programs.edit',
]);

Route::get('programs', [
    'as' => 'admin.programs.index',
    'uses' => 'ProgramController@index',
    'middleware' => 'can:admin.programs.index',
]);

Route::post('programs', [
    'as' => 'admin.programs.store',
    'uses' => 'ProgramController@store',
    'middleware' => 'can:admin.programs.create',
]);

Route::get('programs/{id}', [
    'as' => 'admin.programs.show',
    'uses' => 'ProgramController@show',
    'middleware' => 'can:admin.programs.edit',
]);

Route::put('programs/{id}', [
    'as' => 'admin.programs.update',
    'uses' => 'ProgramController@update',
    'middleware' => 'can:admin.programs.edit',
]);

Route::delete('programs/{id}', [
    'as' => 'admin.programs.destroy',
    'uses' => 'ProgramController@destroy',
    'middleware' => 'can:admin.programs.destroy',
]);
