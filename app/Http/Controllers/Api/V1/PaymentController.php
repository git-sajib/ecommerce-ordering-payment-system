<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\StorePaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use App\Services\PaymentService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
     * Payment History
     * --------------------------------------------------------------------------
     * Get all payments for the authenticated user.
     */
    #[OA\Get(
        path: '/api/v1/payments',
        summary: 'Get authenticated user payments',
        tags: ['Payments'],
        security: [['sanctum' => []]]
    )]
    #[OA\Response(
        response: 200,
        description: 'Payments retrieved successfully.'
    )]
    #[OA\Response(
        response: 401,
        description: 'Unauthenticated.'
    )]
    public function index(Request $request): JsonResponse
    {
        return $this->success(
            PaymentResource::collection(
                $this->paymentService->all(
                    $request->user()
                )
            ),
            'Payments retrieved successfully.'
        );
    }

    /**
     * --------------------------------------------------------------------------
     * Payment Details
     * --------------------------------------------------------------------------
     * Get a specific payment for the authenticated user.
     */
    #[OA\Get(
        path: '/api/v1/payments/{payment}',
        summary: 'Get payment details',
        tags: ['Payments'],
        security: [['sanctum' => []]]
    )]
    #[OA\Parameter(
        name: 'payment',
        description: 'Payment ID',
        in: 'path',
        required: true,
        schema: new OA\Schema(
            type: 'integer',
            example: 1
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Payment retrieved successfully.'
    )]
    #[OA\Response(
        response: 404,
        description: 'Payment not found.'
    )]
    public function show(
        Request $request,
        Payment $payment
    ): JsonResponse {
        return $this->success(
            new PaymentResource(
                $this->paymentService->find(
                    $request->user(),
                    $payment
                )
            ),
            'Payment retrieved successfully.'
        );
    }

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
        $order = $request->user()
            ->orders()
            ->findOrFail(
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
