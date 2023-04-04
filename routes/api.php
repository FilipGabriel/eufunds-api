<?php

use Illuminate\Support\Facades\Route;

Route::get('email/verify/{id}/{hash}', [
    'as' => 'verification.verify',
    'uses' => 'VerifyEmailController@verify',
    'middleware' => ['signed', 'throttle:6,1'],
]);
