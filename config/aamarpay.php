<?php

return [
    'store_id' => env('AAMARPAY_STORE_ID',''),
    'signature_key' => env('AAMARPAY_KEY',''),
    'sandbox' => env('AAMARPAY_SANDBOX', true),
    'redirect_url' => [
        'success' => [
            'url' => env('AAMARPAY_SUCCESS_URL','') // payment.success
        ],
        'cancel' => [
            'url' => env('AAMARPAY_CANCEL_URL','') // payment/cancel or you can use route also
        ]
    ]
];
