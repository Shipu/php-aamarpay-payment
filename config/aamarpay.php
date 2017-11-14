<?php

return [
    'store_id' => env('AAMARPAY_STORE_ID',''),
    'signature_key' => env('AAMARPAY_KEY',''),
    'sandbox' => env('AAMARPAY_SANDBOX', true),
    'redirect_url' => [
        'success' => [
            'route' => '' // payment.success
        ],
        'cancel' => [
            'url' => '' // payment/cancel or you can use route also
        ]
    ]
];
