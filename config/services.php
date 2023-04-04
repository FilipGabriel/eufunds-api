<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => env('SES_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => \Modules\User\Entities\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'smartbill' => [
        'cif' => env('SMARTBILL_API_CIF'),
        'series' => env('SMARTBILL_API_SERIES'),
        'key' => env('SMARTBILL_API_KEY'),
        'url' => env('SMARTBILL_API_URL'),
    ],

    'mobilpay' => [
        'signature' => env('MOBILPAY_SIGNATURE'),
        'public_cer' => env('MOBILPAY_PUBLIC_CER'),
        'private_key' => env('MOBILPAY_PRIVATE_KEY'),
    ],

    'termene' => [
        'baseUrl' => env('TERMENE_BASE_URL'),
        'username' => env('TERMENE_USERNAME'),
        'password' => env('TERMENE_PASSWORD'),
        'key' => env('TERMENE_KEY'),
    ],

    'zoom' => [
        'url' => env('ZOOM_API_URL'),
        'api_key' => env('ZOOM_API_KEY'),
        'secret_key' => env('ZOOM_SECRET_KEY')
    ],
];
