<?php

namespace App\Services;

use App\Contracts\PaymentGatewayInterface;
use App\Exceptions\PaymentGatewayNotFoundException;
use App\Models\PaymentGateway as PaymentGatewayModel;
use App\Services\PaymentGateways\CreditCardGateway;
use App\Services\PaymentGateways\PayPalGateway;

class PaymentGatewayFactory
{
    protected array $gateways = [];

    public function __construct()
    {
        $this->loadGateways();
    }

    protected function loadGateways(): void
    {
        $this->gateways = array_merge(
            config('payment.gateways', []),
            [
                'credit_card' => CreditCardGateway::class,
                'paypal' => PayPalGateway::class,
            ]
        );
    }

    public function make(string $gatewayName): PaymentGatewayInterface
    {
        if (isset($this->gateways[$gatewayName])) {
            return new $this->gateways[$gatewayName];
        }

        $gatewayModel = PaymentGatewayModel::where('name', $gatewayName)
            ->where('is_active', true)
            ->first();

        if ($gatewayModel && class_exists($gatewayModel->class)) {
            $this->register($gatewayName, $gatewayModel->class);
            return new $gatewayModel->class;
        }

        throw new PaymentGatewayNotFoundException("Payment gateway '{$gatewayName}' not found");
    }

    public function register(string $name, string $class): void
    {
        $this->gateways[$name] = $class;
    }
}

