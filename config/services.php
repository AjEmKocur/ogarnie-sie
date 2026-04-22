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
        'require_python' => (bool) env('MODERATION_REQUIRE_PYTHON', false),
        'debug_source' => (bool) env('MODERATION_DEBUG_SOURCE', false),
    ],

    'openai' => [
        'key' => env('OPENAI_API_KEY'),
        'moderation_enabled' => (bool) env('OPENAI_MODERATION_ENABLED', false),
        'moderation_model' => env('OPENAI_MODERATION_MODEL', 'omni-moderation-latest'),
        'timeout_seconds' => (int) env('OPENAI_TIMEOUT_SECONDS', 12),
    ],

    'news_analytics' => [
        'enabled' => filter_var(env('NEWS_ANALYTICS_ENABLED', true), FILTER_VALIDATE_BOOL),
        'python_url' => env('NEWS_ANALYTICS_PYTHON_URL', 'http://moderation-api:8001'),
        'timeout_seconds' => (float) env('NEWS_ANALYTICS_TIMEOUT_SECONDS', 2.5),
        'popular_days' => (int) env('NEWS_ANALYTICS_POPULAR_DAYS', 30),
        'popular_limit' => (int) env('NEWS_ANALYTICS_POPULAR_LIMIT', 5),
        'cache_seconds' => (int) env('NEWS_ANALYTICS_CACHE_SECONDS', 120),
    ],

    'turnstile' => [
        'enabled' => filter_var(env('TURNSTILE_ENABLED', false), FILTER_VALIDATE_BOOL),
        'site_key' => env('TURNSTILE_SITE_KEY'),
        'secret_key' => env('TURNSTILE_SECRET_KEY'),
        'verify_url' => env('TURNSTILE_VERIFY_URL', 'https://challenges.cloudflare.com/turnstile/v0/siteverify'),
        'timeout_seconds' => (float) env('TURNSTILE_TIMEOUT_SECONDS', 5),
    ],

];
