<?php

namespace Tests\Unit\PaymentGateways;

use App\Services\PaymentGateways\CreditCardGateway;
use PHPUnit\Framework\TestCase;

class CreditCardGatewayTest extends TestCase
{
    public function test_processes_payment_successfully()
    {
        $gateway = new CreditCardGateway();
        $result = $gateway->process([
            'amount' => 100.00,
            'card_number' => '4111111111111111',
        ]);

        $this->assertTrue($result['success']);
        $this->assertArrayHasKey('transaction_id', $result);
        $this->assertStringStartsWith('CC_', $result['transaction_id']);
    }

    public function test_fails_with_invalid_card()
    {
        $gateway = new CreditCardGateway();
        $result = $gateway->process([
            'amount' => 100.00,
            'card_number' => '4111111111111110',
        ]);

        $this->assertFalse($result['success']);
    }

    public function test_fails_with_empty_data()
    {
        $gateway = new CreditCardGateway();
        $result = $gateway->process([]);

        $this->assertFalse($result['success']);
    }

    public function test_returns_gateway_name()
    {
        $gateway = new CreditCardGateway();
        $this->assertEquals('credit_card', $gateway->getName());
    }
}

