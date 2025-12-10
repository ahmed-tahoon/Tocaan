<?php

namespace App\Services\PaymentGateways;

use App\Contracts\PaymentGatewayInterface;

class PayPalGateway implements PaymentGatewayInterface
{
    public function process(array $data): array
    {
        $amount = $data['amount'] ?? 0;
        $email = $data['email'] ?? '';

        if (empty($email) || $amount <= 0) {
            return [
                'success' => false,
                'message' => 'Invalid payment data',
            ];
        }

        $simulatedSuccess = filter_var($email, FILTER_VALIDATE_EMAIL) !== false;

        return [
            'success' => $simulatedSuccess,
            'transaction_id' => 'PP_' . uniqid(),
            'message' => $simulatedSuccess ? 'Payment processed successfully' : 'Payment failed',
        ];
    }

    public function getName(): string
    {
        return 'paypal';
    }
}

