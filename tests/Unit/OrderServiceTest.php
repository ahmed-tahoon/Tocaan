<?php

namespace Tests\Unit;

use App\Exceptions\OrderCannotBeDeletedException;
use App\Models\Order;
use App\Models\Payment;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_calculates_order_total_correctly()
    {
        $service = new OrderService();
        $reflection = new \ReflectionClass($service);
        $method = $reflection->getMethod('calculateTotal');
        $method->setAccessible(true);

        $items = [
            ['quantity' => 2, 'price' => 10.00],
            ['quantity' => 3, 'price' => 5.00],
        ];

        $total = $method->invoke($service, $items);

        $this->assertEquals(35.00, $total);
    }

    public function test_throws_exception_when_deleting_order_with_payments()
    {
        $this->expectException(OrderCannotBeDeletedException::class);

        $order = Order::factory()->create();
        Payment::factory()->create(['order_id' => $order->id]);

        $service = new OrderService();
        $service->delete($order);
    }

    public function test_deletes_order_without_payments()
    {
        $order = Order::factory()->create();

        $service = new OrderService();
        $result = $service->delete($order);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
    }
}

