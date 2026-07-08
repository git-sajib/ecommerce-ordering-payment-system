<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\StorePaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Models\Order;
use App\Services\PaymentService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected PaymentService $paymentService
    ) {}

    /**
     * Process a payment.
     */
    public function store(StorePaymentRequest $request): JsonResponse
    {
        $order = Order::findOrFail(
            $request->validated()['order_id']
        );

        $payment = $this->paymentService->pay(
            $order,
            $request->validated()['provider']
        );

        return $this->success(
            new PaymentResource($payment),
            'Payment completed successfully.',
            201
        );
    }
}
