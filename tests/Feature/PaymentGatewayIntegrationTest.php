<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use App\Services\PaymentService;
use App\Services\PaymentGatewayFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentGatewayIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->token = auth()->login($this->user);
    }

    public function test_credit_card_payment_flow()
    {
        $order = Order::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'confirmed',
            'total' => 100.00,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson("/api/orders/{$order->id}/payments", [
                'payment_method' => 'credit_card',
                'gateway' => 'credit_card',
                'card_number' => '4111111111111111',
            ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('payments', [
            'order_id' => $order->id,
            'gateway' => 'credit_card',
            'status' => 'successful',
        ]);
    }

    public function test_paypal_payment_flow()
    {
        $order = Order::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'confirmed',
            'total' => 200.00,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson("/api/orders/{$order->id}/payments", [
                'payment_method' => 'paypal',
                'gateway' => 'paypal',
                'email' => 'test@example.com',
            ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('payments', [
            'order_id' => $order->id,
            'gateway' => 'paypal',
        ]);
    }

    public function test_payment_failure_handling()
    {
        $order = Order::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'confirmed',
            'total' => 100.00,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson("/api/orders/{$order->id}/payments", [
                'payment_method' => 'credit_card',
                'gateway' => 'credit_card',
                'card_number' => '4111111111111110',
            ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('payments', [
            'order_id' => $order->id,
            'status' => 'failed',
        ]);
    }
}

