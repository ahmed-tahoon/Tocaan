<?php

namespace Tests\Unit\PaymentGateways;

use App\Services\PaymentGateways\PayPalGateway;
use PHPUnit\Framework\TestCase;

class PayPalGatewayTest extends TestCase
{
    public function test_processes_payment_successfully()
    {
        $gateway = new PayPalGateway();
        $result = $gateway->process([
            'amount' => 100.00,
            'email' => 'test@example.com',
        ]);

        $this->assertTrue($result['success']);
        $this->assertArrayHasKey('transaction_id', $result);
        $this->assertStringStartsWith('PP_', $result['transaction_id']);
    }

    public function test_fails_with_invalid_email()
    {
        $gateway = new PayPalGateway();
        $result = $gateway->process([
            'amount' => 100.00,
            'email' => 'invalid-email',
        ]);

        $this->assertFalse($result['success']);
    }

    public function test_fails_with_empty_data()
    {
        $gateway = new PayPalGateway();
        $result = $gateway->process([]);

        $this->assertFalse($result['success']);
    }

    public function test_returns_gateway_name()
    {
        $gateway = new PayPalGateway();
        $this->assertEquals('paypal', $gateway->getName());
    }
}

