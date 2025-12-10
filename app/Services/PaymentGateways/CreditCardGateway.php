<?php

namespace App\Services\PaymentGateways;

use App\Contracts\PaymentGatewayInterface;

class CreditCardGateway implements PaymentGatewayInterface
{
    public function process(array $data): array
    {
        $amount = $data['amount'] ?? 0;
        $cardNumber = $data['card_number'] ?? '';

        if (empty($cardNumber) || $amount <= 0) {
            return [
                'success' => false,
                'message' => 'Invalid payment data',
            ];
        }

        $simulatedSuccess = substr($cardNumber, -1) !== '0';

        return [
            'success' => $simulatedSuccess,
            'transaction_id' => 'CC_' . uniqid(),
            'message' => $simulatedSuccess ? 'Payment processed successfully' : 'Payment failed',
        ];
    }

    public function getName(): string
    {
        return 'credit_card';
    }
}

