<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class CacheService
{
    protected int $defaultTtl = 3600;

    public function rememberOrder(int $orderId, callable $callback)
    {
        return Cache::remember("order.{$orderId}", $this->defaultTtl, $callback);
    }

    public function rememberUserOrders(int $userId, string $status, callable $callback)
    {
        $key = "user.{$userId}.orders.{$status}";
        return Cache::tags(["user.{$userId}.orders"])->remember($key, $this->defaultTtl, $callback);
    }

    public function rememberPaymentGateways(callable $callback)
    {
        return Cache::remember('payment.gateways', 7200, $callback);
    }

    public function forgetOrder(int $orderId): void
    {
        Cache::forget("order.{$orderId}");
    }

    public function forgetUserOrders(int $userId): void
    {
        Cache::tags(["user.{$userId}.orders"])->flush();
    }

    public function forgetPaymentGateways(): void
    {
        Cache::forget('payment.gateways');
    }
}

