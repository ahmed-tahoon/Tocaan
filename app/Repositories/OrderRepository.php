<?php

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class OrderRepository extends BaseRepository
{
    public function __construct(Order $model)
    {
        parent::__construct($model);
    }

    public function findByUser(int $userId, ?string $status = null): Collection
    {
        $query = $this->newQuery()->where('user_id', $userId);

        if ($status) {
            $query->where('status', $status);
        }

        return $query->get();
    }

    public function paginateByUser(int $userId, ?string $status = null, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->newQuery()
            ->where('user_id', $userId)
            ->with(['items', 'payments']);

        if ($status) {
            $query->where('status', $status);
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function findWithRelations(int $id): ?Order
    {
        return $this->newQuery()
            ->with(['items', 'payments', 'user'])
            ->find($id);
    }

    public function findByStatus(string $status): Collection
    {
        return $this->newQuery()
            ->where('status', $status)
            ->get();
    }

    public function getTotalByUser(int $userId): float
    {
        return $this->newQuery()
            ->where('user_id', $userId)
            ->sum('total');
    }
}

