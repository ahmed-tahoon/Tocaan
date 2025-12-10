<?php

namespace App\Observers;

use App\Events\OrderStatusChanged;
use App\Models\Order;
use Illuminate\Support\Facades\Cache;

class OrderObserver
{
    public function created(Order $order): void
    {
        $this->clearUserCache($order->user_id);
    }

    public function updated(Order $order): void
    {
        if ($order->isDirty('status')) {
            event(new OrderStatusChanged(
                $order,
                $order->getOriginal('status'),
                $order->status
            ));
        }

        $this->clearUserCache($order->user_id);
        $this->clearOrderCache($order->id);
    }

    public function deleted(Order $order): void
    {
        $this->clearUserCache($order->user_id);
        $this->clearOrderCache($order->id);
    }

    protected function clearUserCache(int $userId): void
    {
        Cache::tags(["user.{$userId}.orders"])->flush();
    }

    protected function clearOrderCache(int $orderId): void
    {
        Cache::forget("order.{$orderId}");
    }
}
