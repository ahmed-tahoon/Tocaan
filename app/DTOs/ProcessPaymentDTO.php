<?php

namespace App\DTOs;

class ProcessPaymentDTO
{
    public function __construct(
        public readonly int $orderId,
        public readonly string $paymentMethod,
        public readonly string $gateway,
        public readonly ?string $paymentId = null,
        public readonly array $gatewayData = []
    ) {
    }

    public static function fromArray(array $data, int $orderId): self
    {
        return new self(
            orderId: $orderId,
            paymentMethod: $data['payment_method'],
            gateway: $data['gateway'],
            paymentId: $data['payment_id'] ?? null,
            gatewayData: $data
        );
    }

    public function toArray(): array
    {
        return [
            'payment_id' => $this->paymentId,
            'payment_method' => $this->paymentMethod,
            'gateway' => $this->gateway,
            ...$this->gatewayData,
        ];
    }
}

