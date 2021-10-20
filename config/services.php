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
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'TPG' =>[
        'serverUrl' => 'https://api.triple-pg.com',
        'webloUrl' => 'https://weblobby.triple-pg.com',
    ],
    
    'TPGTEST' => [
        'serverUrl' => 'https://stagingapi.triple-pg.com',
        'webloUrl' => 'https://stagingweblobby.triple-pg.com',
    ],

    'AMEBA' =>[
        'url' => 'https://api.fafafa3388.com',
        'key' => 'O/sKRB1n/uNzaVaUzFiHcALimmbSQ4u8',
    ],

    'AMEBA-TEST' =>[
        'url' => 'https://api-snd.fafafa3388.com',
        'key' => 'i/ykKpZENszGfHqwjASrNlckYJt/zZbi',
    ],

];
