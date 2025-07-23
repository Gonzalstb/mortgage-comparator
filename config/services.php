<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'banks' => [
        'lacaixa' => [
            'api_url' => env('LACAIXA_API_URL'),
            'api_key' => env('LACAIXA_API_KEY'),
            'client_id' => env('LACAIXA_CLIENT_ID'),
        ],
        'santander' => [
            'api_url' => env('SANTANDER_API_URL'),
            'api_key' => env('SANTANDER_API_KEY'),
        ],
        'ing' => [
            'api_url' => env('ING_API_URL', 'https://api.ing.es/mortgage/v1'),
            'app_id' => env('ING_APP_ID'),
            'api_key' => env('ING_API_KEY'),
        ],
        'bbva' => [
            'api_url' => env('BBVA_API_URL', 'https://apis.bbva.com'),
            'client_id' => env('BBVA_CLIENT_ID'),
            'client_secret' => env('BBVA_CLIENT_SECRET'),
            'access_token' => env('BBVA_ACCESS_TOKEN'),
        ],
        'sabadell' => [
            'api_url' => env('SABADELL_API_URL'),
            'api_key' => env('SABADELL_API_KEY'),
        ],
    ],
    
    'mortgage' => [
        'use_scraping' => env('MORTGAGE_USE_SCRAPING', true),
        'cache_ttl' => env('MORTGAGE_CACHE_TTL', 3600), // 1 hora
        'rate_limit' => env('MORTGAGE_RATE_LIMIT', 60), // requests por minuto
    ],
];
