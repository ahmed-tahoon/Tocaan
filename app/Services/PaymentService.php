<?php

namespace App\Services;

use App\DTOs\ProcessPaymentDTO;
use App\Exceptions\OrderNotConfirmedException;
use App\Models\Order;
use App\Models\Payment;
use App\Repositories\PaymentRepository;
use App\Services\PaymentGatewayFactory;
use Illuminate\Support\Str;

class PaymentService
{
    public function __construct(
        protected PaymentGatewayFactory $gatewayFactory,
        protected PaymentRepository $paymentRepository
    ) {
    }

    public function process(Order $order, ProcessPaymentDTO $dto): Payment
    {
        if (!$order->isConfirmed()) {
            throw new OrderNotConfirmedException();
        }

        $gateway = $this->gatewayFactory->make($dto->gateway);
        
        $gatewayData = array_merge($dto->toArray(), [
            'amount' => $order->total,
            'order_id' => $order->id,
        ]);

        $result = $gateway->process($gatewayData);

        $payment = $this->paymentRepository->create([
            'payment_id' => $dto->paymentId ?? Str::uuid()->toString(),
            'order_id' => $order->id,
            'status' => $result['success'] ? 'successful' : 'failed',
            'payment_method' => $dto->paymentMethod,
            'gateway' => $gateway->getName(),
            'metadata' => $result,
        ]);

        return $payment;
    }
}

