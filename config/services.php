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

    'moderation' => [
        'python_enabled' => (bool) env('MODERATION_PYTHON_ENABLED', true),
        'python_url' => env('MODERATION_PYTHON_URL', 'http://moderation-api:8001/moderate'),
        'timeout_seconds' => (int) env('MODERATION_TIMEOUT_SECONDS', 5),
    ],

    'openai' => [
        'key' => env('OPENAI_API_KEY'),
        'moderation_enabled' => env('OPENAI_MODERATION_ENABLED', false),
        'moderation_model' => env('OPENAI_MODERATION_MODEL', 'omni-moderation-latest'),
        'timeout_seconds' => env('OPENAI_TIMEOUT_SECONDS', 12),
    ],

];
