<?php

return [
    'username'       => env('SP_USERNAME'),
    'password'       => env('SP_PASSWORD'),
    'order_prefix'   => env('SP_PREFIX'),
    'api_endpoint'   => env('SHURJOPAY_API', 'https://engine.shurjopayment.com'),
    'callback_url'   => env('SP_CALLBACK'),
    'log_path'       => env('SP_LOG_LOCATION', storage_path('logs/shurjopay')),
    'ssl_verifypeer' => env('CURLOPT_SSL_VERIFYPEER', 1),
];