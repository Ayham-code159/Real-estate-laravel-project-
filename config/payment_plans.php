<?php

return [
    'plans' => [
        'basic' => [
            'key' => 'basic',
            'name' => 'Basic',
            'description' => 'Free plan. You cannot create business accounts.',
            'price_usd' => 0,
            'price_cents' => 0,
            'business_account_limit' => 0,
            'rank' => 0,
        ],

        'plus' => [
            'key' => 'plus',
            'name' => 'Plus',
            'description' => 'Create one business account.',
            'price_usd' => 20,
            'price_cents' => 2000,
            'business_account_limit' => 1,
            'rank' => 1,
        ],

        'pro' => [
            'key' => 'pro',
            'name' => 'Pro',
            'description' => 'Create up to five business accounts.',
            'price_usd' => 100,
            'price_cents' => 10000,
            'business_account_limit' => 5,
            'rank' => 2,
        ],
    ],
];
    