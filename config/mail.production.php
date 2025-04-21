<?php

/**
 * Production-specific mail configuration
 * This file contains the mail configuration for the production environment
 */

return [
    'default' => 'smtp',
    
    'mailers' => [
        'smtp' => [
            'transport' => 'smtp',
            'host' => env('MAIL_HOST', 'smtp.gmail.com'),
            'port' => env('MAIL_PORT', 587),
            'encryption' => env('MAIL_ENCRYPTION', 'tls'),
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
            'timeout' => null,
            'local_domain' => env('MAIL_EHLO_DOMAIN'),
        ],
    ],
    
    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'no-reply@ntu-health-booking.com'),
        'name' => env('MAIL_FROM_NAME', 'Beauty Spa Booking'),
    ],
];
