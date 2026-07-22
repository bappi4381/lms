<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default SMS Driver
    |--------------------------------------------------------------------------
    |
    | "log" simply writes the OTP to the log file — perfect for local/dev
    | environments with no SMS gateway contract yet. Switch to "bulksmsbd"
    | (or add your own driver) once you have real gateway credentials.
    |
    */
    'default' => env('SMS_DRIVER', 'log'),

    'otp' => [
        'length' => 6,
        'expires_in_minutes' => 5,
        'max_attempts' => 5,
    ],

    'drivers' => [
        'log' => [
            'driver' => \App\Services\Sms\LogSmsDriver::class,
        ],

        // Generic Bangladeshi bulk SMS gateway (BulkSMSBD-style HTTP API).
        // Swap the base_url/params for your actual provider's contract.
        'bulksmsbd' => [
            'driver' => \App\Services\Sms\BulkSmsBdDriver::class,
            'api_key' => env('SMS_API_KEY'),
            'sender_id' => env('SMS_SENDER_ID'),
            'base_url' => env('SMS_BASE_URL', 'http://bulksmsbd.net/api/smsapi'),
        ],

        'twilio' => [
            'driver' => \App\Services\Sms\TwilioSmsDriver::class,
            'sid' => env('TWILIO_SID'),
            'token' => env('TWILIO_AUTH_TOKEN'),
            'from' => env('TWILIO_FROM'),
        ],
    ],
];
