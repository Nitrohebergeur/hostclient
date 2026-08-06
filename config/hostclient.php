<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Company Information
    |--------------------------------------------------------------------------
    */
    'company_name' => env('HOSTCLIENT_COMPANY_NAME', 'HostClient'),
    'company_logo' => env('HOSTCLIENT_COMPANY_LOGO', '/images/logo.png'),
    'company_email' => env('MAIL_FROM_ADDRESS', 'contact@hostclient.local'),
    'company_phone' => env('HOSTCLIENT_COMPANY_PHONE'),
    'company_address' => env('HOSTCLIENT_COMPANY_ADDRESS'),

    /*
    |--------------------------------------------------------------------------
    | Currency & Localization
    |--------------------------------------------------------------------------
    */
    'currency' => env('HOSTCLIENT_CURRENCY', 'EUR'),
    'currency_symbol' => env('HOSTCLIENT_CURRENCY_SYMBOL', '€'),
    'timezone' => env('HOSTCLIENT_TIMEZONE', 'Europe/Paris'),
    'locale' => env('HOSTCLIENT_LOCALE', 'fr'),
    'date_format' => env('HOSTCLIENT_DATE_FORMAT', 'd/m/Y'),
    'datetime_format' => env('HOSTCLIENT_DATETIME_FORMAT', 'd/m/Y H:i'),

    /*
    |--------------------------------------------------------------------------
    | Billing Configuration
    |--------------------------------------------------------------------------
    */
    'tax_rate' => env('HOSTCLIENT_TAX_RATE', 20.00),
    'tax_name' => env('HOSTCLIENT_TAX_NAME', 'TVA'),
    'invoice_prefix' => env('HOSTCLIENT_INVOICE_PREFIX', 'INV-'),
    'order_prefix' => env('HOSTCLIENT_ORDER_PREFIX', 'ORD-'),
    'ticket_prefix' => env('HOSTCLIENT_TICKET_PREFIX', 'TKT-'),
    'invoice_due_days' => env('HOSTCLIENT_INVOICE_DUE_DAYS', 14),
    'invoice_logo' => env('HOSTCLIENT_INVOICE_LOGO'),
    
    /*
    |--------------------------------------------------------------------------
    | Service Automation
    |--------------------------------------------------------------------------
    */
    'auto_suspend_days' => env('HOSTCLIENT_AUTO_SUSPEND_DAYS', 7),
    'auto_terminate_days' => env('HOSTCLIENT_AUTO_TERMINATE_DAYS', 14),
    'send_invoice_before_days' => env('HOSTCLIENT_SEND_INVOICE_BEFORE_DAYS', 7),
    'send_reminder_days' => env('HOSTCLIENT_SEND_REMINDER_DAYS', 3),

    /*
    |--------------------------------------------------------------------------
    | Feature Flags
    |--------------------------------------------------------------------------
    */
    'features' => [
        'registration' => env('HOSTCLIENT_REGISTRATION', true),
        'email_verification' => env('HOSTCLIENT_EMAIL_VERIFICATION', true),
        'two_factor' => env('HOSTCLIENT_TWO_FACTOR', true),
        'api' => env('HOSTCLIENT_API', true),
        'maintenance_mode' => env('HOSTCLIENT_MAINTENANCE', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Configuration
    |--------------------------------------------------------------------------
    */
    'modules' => [
        'path' => base_path('modules'),
        'namespace' => 'Modules',
        'enabled' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | Theme Configuration
    |--------------------------------------------------------------------------
    */
    'theme' => [
        'active' => env('HOSTCLIENT_THEME', 'default'),
        'path' => resource_path('themes'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Security
    |--------------------------------------------------------------------------
    */
    'security' => [
        'max_login_attempts' => env('HOSTCLIENT_MAX_LOGIN_ATTEMPTS', 5),
        'lockout_duration' => env('HOSTCLIENT_LOCKOUT_DURATION', 15), // minutes
        'password_min_length' => env('HOSTCLIENT_PASSWORD_MIN_LENGTH', 8),
        'session_lifetime' => env('SESSION_LIFETIME', 120), // minutes
    ],

    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    */
    'notifications' => [
        'channels' => ['mail', 'database'],
        'discord_webhook' => env('DISCORD_WEBHOOK_URL'),
        'slack_webhook' => env('SLACK_WEBHOOK_URL'),
        'telegram_bot_token' => env('TELEGRAM_BOT_TOKEN'),
        'telegram_chat_id' => env('TELEGRAM_CHAT_ID'),
    ],

    /*
    |--------------------------------------------------------------------------
    | API Configuration
    |--------------------------------------------------------------------------
    */
    'api' => [
        'version' => 'v1',
        'rate_limit' => env('HOSTCLIENT_API_RATE_LIMIT', 60), // per minute
        'documentation' => env('HOSTCLIENT_API_DOCS', true),
    ],
];
