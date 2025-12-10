<?php

namespace App\Services;

use App\DTOs\CreateOrderDTO;
use App\Events\OrderStatusChanged;
use App\Exceptions\OrderCannotBeDeletedException;
use App\Models\Order;
use App\Models\OrderItem;
use App\Repositories\OrderRepository;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(
        protected OrderRepository $orderRepository
    ) {
    }

    public function create(CreateOrderDTO $dto): Order
    {
        return DB::transaction(function () use ($dto) {
            $order = $this->orderRepository->create([
                'user_id' => $dto->userId,
                'status' => 'pending',
                'total' => $dto->total,
            ]);

            foreach ($dto->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_name' => $item['product_name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }

            return $order->load('items');
        });
    }

    public function update(Order $order, array $data): Order
    {
        return DB::transaction(function () use ($order, $data) {
            if (isset($data['items'])) {
                $order->items()->delete();
                
                $total = $this->calculateTotal($data['items']);

                foreach ($data['items'] as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_name' => $item['product_name'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                    ]);
                }

                $order->update(['total' => $total]);
            }

            if (isset($data['status']) && $order->status !== $data['status']) {
                $oldStatus = $order->status;
                $order->update(['status' => $data['status']]);
                event(new OrderStatusChanged($order, $oldStatus, $data['status']));
            }

            return $order->fresh()->load('items');
        });
    }

    public function delete(Order $order): bool
    {
        if (!$order->canBeDeleted()) {
            throw new OrderCannotBeDeletedException();
        }

        return $order->delete();
    }

    protected function calculateTotal(array $items): float
    {
        $total = 0;

        foreach ($items as $item) {
            $total += ($item['quantity'] * $item['price']);
        }

        return $total;
    }
}

