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
     * Create a payment intent.
     */
    public function store(StorePaymentRequest $request): JsonResponse
    {
        $order = Order::findOrFail(
            $request->validated()['order_id']
        );

        $result = $this->paymentService->pay(
            $order,
            $request->validated()['provider']
        );

        return $this->success(
            [
                'payment' => new PaymentResource($result['payment']),
                'client_secret' => $result['gateway']['client_secret'],
                'checkout_url' => $result['gateway']['checkout_url'],
                'payment_intent_id' => $result['gateway']['transaction_id'],
            ],
            'Payment intent created successfully.',
            201
        );
    }
}
