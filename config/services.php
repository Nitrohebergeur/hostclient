<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides a sensible
    | default structure for services used by HostClient.
    |
    */

    'stripe' => [
        'key'           => env('STRIPE_KEY'),
        'secret'        => env('STRIPE_SECRET'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    ],

    'paypal' => [
        'client_id' => env('PAYPAL_CLIENT_ID'),
        'secret'    => env('PAYPAL_SECRET'),
        'mode'      => env('PAYPAL_MODE', 'sandbox'), // sandbox | live
    ],

    'mollie' => [
        'key' => env('MOLLIE_KEY'),
    ],

    'mail' => [
        'mailers' => [
            'smtp' => [
                'transport' => 'smtp',
                'host'      => env('MAIL_HOST', 'smtp.mailgun.org'),
                'port'      => env('MAIL_PORT', 587),
                'encryption'=> env('MAIL_ENCRYPTION', 'tls'),
                'username'  => env('MAIL_USERNAME'),
                'password'  => env('MAIL_PASSWORD'),
                'timeout'   => null,
                'local_domain' => env('MAIL_EHLO_DOMAIN'),
            ],
        ],

        'from' => [
            'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
            'name'    => env('MAIL_FROM_NAME', 'Example'),
        ],
    ],

];