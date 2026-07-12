<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Services\OrderService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Orders',
    description: 'Order Management APIs'
)]
class OrderController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected OrderService $orderService
    ) {}

    /**
     * --------------------------------------------------------------------------
     * Create Order
     * --------------------------------------------------------------------------
     * Create a new order from the selected products.
     * The total amount is calculated automatically based on the submitted
     * products and quantities.
     */
    #[OA\Post(
        path: '/api/v1/orders',
        summary: 'Create order',
        tags: ['Orders'],
        security: [['sanctum' => []]]
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['items'],
            properties: [

                new OA\Property(
                    property: 'items',
                    type: 'array',
                    items: new OA\Items(
                        properties: [

                            new OA\Property(
                                property: 'product_id',
                                type: 'integer',
                                example: 1
                            ),

                            new OA\Property(
                                property: 'quantity',
                                type: 'integer',
                                example: 2
                            ),
                        ],
                        type: 'object'
                    )
                ),
            ]
        )
    )]
    #[OA\Response(
        response: 201,
        description: 'Order created successfully.'
    )]
    #[OA\Response(
        response: 401,
        description: 'Unauthenticated.'
    )]
    #[OA\Response(
        response: 422,
        description: 'Validation failed.'
    )]
    public function store(StoreOrderRequest $request): JsonResponse
    {
        $order = $this->orderService->create(
            $request->user(),
            $request->validated()['items']
        );

        return $this->success(
            new OrderResource($order),
            'Order created successfully.',
            201
        );
    }
}
