<?php

namespace App\DTOs;

class CreateOrderDTO
{
    public function __construct(
        public readonly int $userId,
        public readonly array $items,
        public readonly float $total
    ) {
    }

    public static function fromArray(array $data, int $userId): self
    {
        $items = $data['items'] ?? [];
        $total = self::calculateTotal($items);

        return new self($userId, $items, $total);
    }

    protected static function calculateTotal(array $items): float
    {
        $total = 0;
        foreach ($items as $item) {
            $total += ($item['quantity'] * $item['price']);
        }
        return $total;
    }
}

