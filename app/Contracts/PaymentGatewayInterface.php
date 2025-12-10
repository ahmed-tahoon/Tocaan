<?php

namespace App\Contracts;

interface PaymentGatewayInterface
{
    public function process(array $data): array;

    public function getName(): string;
}

