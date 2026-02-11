<?php

return [
    /*
    |--------------------------------------------------------------------------
    | SMS Driver
    |--------------------------------------------------------------------------
    |
    | Supported drivers:
    | - log: write SMS messages to the log for inspection
    | - custom: placeholder for real gateway integration
    |
    */
    'driver' => env('SMS_DRIVER', 'log'),

    /*
    |--------------------------------------------------------------------------
    | Default Sender ID / From
    |--------------------------------------------------------------------------
    */
    'from' => env('SMS_FROM', 'ATCLSACCOS'),
];

