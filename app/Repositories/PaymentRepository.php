<?php

namespace App\Repositories;

use App\Models\Payment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class PaymentRepository extends BaseRepository
{
    public function __construct(Payment $model)
    {
        parent::__construct($model);
    }

    public function findByOrder(int $orderId): Collection
    {
        return $this->newQuery()
            ->where('order_id', $orderId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function findByUser(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->newQuery()
            ->whereHas('order', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->with('order')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function findByStatus(string $status): Collection
    {
        return $this->newQuery()
            ->where('status', $status)
            ->get();
    }

    public function findByGateway(string $gateway): Collection
    {
        return $this->newQuery()
            ->where('gateway', $gateway)
            ->get();
    }

    public function getTotalByOrder(int $orderId): float
    {
        return $this->newQuery()
            ->where('order_id', $orderId)
            ->where('status', 'successful')
            ->sum('amount');
    }
}

