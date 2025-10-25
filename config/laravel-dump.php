<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Laravel Dump macOS Viewer Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for the Laravel Dump macOS Viewer
    | integration. The package will auto-detect the macOS app, but you can
    | override these settings if needed.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Server URL
    |--------------------------------------------------------------------------
    |
    | The URL where the macOS Laravel Dump Viewer is running. If null,
    | the package will auto-detect the server on common ports (9999, 9998, 9997).
    |
    */
    'server_url' => env('LARAVEL_DUMP_SERVER_URL', null),

    /*
    |--------------------------------------------------------------------------
    | Auto Detection
    |--------------------------------------------------------------------------
    |
    | Whether to automatically detect the macOS app server. If disabled,
    | you must set the server_url manually.
    |
    */
    'auto_detect' => env('LARAVEL_DUMP_AUTO_DETECT', true),

    /*
    |--------------------------------------------------------------------------
    | Enabled
    |--------------------------------------------------------------------------
    |
    | Whether the Laravel Dump integration is enabled. Set to false to
    | disable all dump sending to the macOS app.
    |
    */
    'enabled' => env('LARAVEL_DUMP_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Ports to Check
    |--------------------------------------------------------------------------
    |
    | The ports to check when auto-detecting the macOS app server.
    | The package will try these ports in order.
    |
    */
    'ports' => [9999, 9998, 9997],

    /*
    |--------------------------------------------------------------------------
    | Timeout
    |--------------------------------------------------------------------------
    |
    | The timeout in seconds for sending dump data to the macOS app.
    | Keep this low to avoid blocking your Laravel application.
    |
    */
    'timeout' => env('LARAVEL_DUMP_TIMEOUT', 1),
];
