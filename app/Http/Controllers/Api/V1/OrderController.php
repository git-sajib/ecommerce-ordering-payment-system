<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\OrderService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
     * Order History
     * --------------------------------------------------------------------------
     * Get all orders for the authenticated user.
     */
    #[OA\Get(
        path: '/api/v1/orders',
        summary: 'Get authenticated user orders',
        tags: ['Orders'],
        security: [['sanctum' => []]]
    )]
    #[OA\Response(
        response: 200,
        description: 'Orders retrieved successfully.'
    )]
    #[OA\Response(
        response: 401,
        description: 'Unauthenticated.'
    )]
    public function index(Request $request): JsonResponse
    {
        return $this->success(
            OrderResource::collection(
                $this->orderService->all(
                    $request->user()
                )
            ),
            'Orders retrieved successfully.'
        );
    }

    /**
     * --------------------------------------------------------------------------
     * Order Details
     * --------------------------------------------------------------------------
     * Get a specific order for the authenticated user.
     */
    #[OA\Get(
        path: '/api/v1/orders/{order}',
        summary: 'Get order details',
        tags: ['Orders'],
        security: [['sanctum' => []]]
    )]
    #[OA\Parameter(
        name: 'order',
        description: 'Order ID',
        in: 'path',
        required: true,
        schema: new OA\Schema(
            type: 'integer',
            example: 1
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Order retrieved successfully.'
    )]
    #[OA\Response(
        response: 404,
        description: 'Order not found.'
    )]
    public function show(
        Request $request,
        Order $order
    ): JsonResponse {
        return $this->success(
            new OrderResource(
                $this->orderService->find(
                    $request->user(),
                    $order
                )
            ),
            'Order retrieved successfully.'
        );
    }

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
