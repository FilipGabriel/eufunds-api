<?php

use Illuminate\Support\Facades\Route;

Route::get('caens', [
    'as' => 'api.caen.index',
    'uses' => 'CaenCodeController@index',
]);
Route::get('banks', [
    'as' => 'api.bank.index',
    'uses' => 'BankController@index',
]);
Route::get('states/{countryCode}', [
    'as' => 'api.state.index',
    'uses' => 'CountryStateController@index',
]);
Route::get('states-full/{countryCode}', [
    'as' => 'api.full_state.index',
    'uses' => 'CountryStateController@fullStates',
]);
Route::get('states-and-regions/{countryCode}', [
    'as' => 'api.state.regions.index',
    'uses' => 'CountryStateController@statesAndRegions',
]);
Route::get('settlements', [
    'as' => 'api.settlement.index',
    'uses' => 'SettlementController@searchSettlement',
]);
Route::get('cities', [
    'as' => 'api.cities.index',
    'uses' => 'SettlementController@searchCity',
]);
Route::get('agencies', [
    'as' => 'api.agency.index',
    'uses' => 'AgencyController@index',
]);
