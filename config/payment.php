<?php

return [
    'gateways' => [
        'credit_card' => \App\Services\PaymentGateways\CreditCardGateway::class,
        'paypal' => \App\Services\PaymentGateways\PayPalGateway::class,
    ],

    'default_gateway' => env('PAYMENT_DEFAULT_GATEWAY', 'credit_card'),

    'timeout' => env('PAYMENT_TIMEOUT', 30),
];

