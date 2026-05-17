<?php

return [
    /*
    |--------------------------------------------------------------------------
    | BulkSMSBD Configuration
    |--------------------------------------------------------------------------
    | .env ফাইলে এই values গুলো রাখুন:
    |   BULKSMSBD_API_KEY=your_api_key_here
    |   BULKSMSBD_SENDER_ID=8809648907648
    */

    'api_key'   => env('BULKSMSBD_API_KEY', 'hHcLbvnc4eDHzT6onaC5'),
    'sender_id' => env('BULKSMSBD_SENDER_ID', '8809648907648'),
    'url'       => 'https://bulksmsbd.net/api/smsapi',
    'timeout'   => 30,
];
