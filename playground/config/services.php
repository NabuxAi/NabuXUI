<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Resend, Postmark, AWS, and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
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

    /*
    |----------------------------------------------------------------------
    | NabuGate — the product's LLM gateway, wired into the /ai studio.
    | With no endpoint the studio says so and serves the local intent-engine
    | drafts instead; with one, POST {model, locale, intent, prompt} returns
    | {name, description, html} and the draft renders in a sandboxed frame.
    |----------------------------------------------------------------------
    */
    'nabugate' => [
        'endpoint' => env('NABUGATE_ENDPOINT'),
        'key' => env('NABUGATE_KEY'),
        'model' => env('NABUGATE_MODEL', 'nabugate-1'),
    ],

];
