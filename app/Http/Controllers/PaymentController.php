<?php

namespace App\Http\Controllers;

use App\DTOs\ProcessPaymentDTO;
use App\Http\Requests\ProcessPaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Models\Order;
use App\Repositories\PaymentRepository;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService,
        protected PaymentRepository $paymentRepository
    ) {
    }

    public function process(ProcessPaymentRequest $request, Order $order): JsonResponse
    {
        if ($order->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        try {
            $dto = ProcessPaymentDTO::fromArray($request->validated(), $order->id);
            $payment = $this->paymentService->process($order, $dto);

            return response()->json(new PaymentResource($payment), 201);
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        }
    }

    public function index(Request $request): JsonResponse
    {
        $payments = $this->paymentRepository->findByUser(auth()->id());

        return PaymentResource::collection($payments)->response();
    }

    public function show(Order $order): JsonResponse
    {
        if ($order->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $payments = $this->paymentRepository->findByOrder($order->id);

        return response()->json(PaymentResource::collection($payments));
    }
}
