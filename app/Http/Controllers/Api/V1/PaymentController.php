<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\StorePaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Models\Order;
use App\Services\PaymentService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Payments',
    description: 'Payment Management APIs'
)]
class PaymentController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected PaymentService $paymentService
    ) {}

    /**
     * --------------------------------------------------------------------------
     * Create Payment
     * --------------------------------------------------------------------------
     * Initiate a payment for an existing order using the selected payment
     * provider (Stripe or bKash). Returns payment details and checkout
     * information required by the frontend.
     */
    #[OA\Post(
        path: '/api/v1/payments',
        summary: 'Create payment',
        tags: ['Payments'],
        security: [['sanctum' => []]]
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['order_id', 'provider'],
            properties: [

                new OA\Property(
                    property: 'order_id',
                    type: 'integer',
                    example: 1
                ),

                new OA\Property(
                    property: 'provider',
                    type: 'string',
                    enum: ['stripe', 'bkash'],
                    example: 'stripe'
                ),
            ]
        )
    )]
    #[OA\Response(
        response: 201,
        description: 'Payment initiated successfully.'
    )]
    #[OA\Response(
        response: 401,
        description: 'Unauthenticated.'
    )]
    #[OA\Response(
        response: 404,
        description: 'Order not found.'
    )]
    #[OA\Response(
        response: 422,
        description: 'Validation failed.'
    )]
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
