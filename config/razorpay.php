<?php

return [
    'key' => env('RAZORPAY_KEY'),
    'secret' => env('RAZORPAY_SECRET'),
    'currency' => env('RAZORPAY_CURRENCY', 'INR'),
    'company_name' => env('RAZORPAY_COMPANY_NAME', 'Eternal Diamonds'),
    'theme_color' => env('RAZORPAY_THEME_COLOR', '#d4af37'),
    'logo' => env('RAZORPAY_LOGO', ''),

    /*
    |--------------------------------------------------------------------------
    | Razorpay Test Mode
    |--------------------------------------------------------------------------
    |
    | Set to true for test mode, false for live mode
    |
    */
    'test_mode' => env('RAZORPAY_TEST_MODE', true),

    /*
    |--------------------------------------------------------------------------
    | Razorpay Webhook Secret
    |--------------------------------------------------------------------------
    |
    | Webhook secret for verifying webhook signatures
    |
    */
    'webhook_secret' => env('RAZORPAY_WEBHOOK_SECRET', ''),

    /*
    |--------------------------------------------------------------------------
    | Payment Methods
    |--------------------------------------------------------------------------
    |
    | Available payment methods for checkout
    |
    */
    'payment_methods' => [
        'card' => true,
        'netbanking' => true,
        'wallet' => true,
        'upi' => true,
        'emi' => false,
    ],
];
