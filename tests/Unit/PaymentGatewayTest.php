<?php

namespace Tests\Unit;

use App\Services\PaymentGatewayFactory;
use App\Services\PaymentGateways\CreditCardGateway;
use App\Services\PaymentGateways\PayPalGateway;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class PaymentGatewayTest extends TestCase
{
    public function test_factory_creates_credit_card_gateway()
    {
        $factory = new PaymentGatewayFactory();
        $gateway = $factory->make('credit_card');

        $this->assertInstanceOf(CreditCardGateway::class, $gateway);
    }

    public function test_factory_creates_paypal_gateway()
    {
        $factory = new PaymentGatewayFactory();
        $gateway = $factory->make('paypal');

        $this->assertInstanceOf(PayPalGateway::class, $gateway);
    }

    public function test_factory_throws_exception_for_invalid_gateway()
    {
        $this->expectException(InvalidArgumentException::class);

        $factory = new PaymentGatewayFactory();
        $factory->make('invalid_gateway');
    }
}
