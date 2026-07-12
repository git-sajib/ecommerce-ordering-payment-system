<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Products',
    description: 'Product Management APIs'
)]
class ProductController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected ProductService $productService
    ) {}

    /**
     * --------------------------------------------------------------------------
     * Get All Products
     * --------------------------------------------------------------------------
     * Retrieve all available products.
     */
    #[OA\Get(
        path: '/api/v1/products',
        summary: 'Get all products',
        tags: ['Products']
    )]
    #[OA\Response(
        response: 200,
        description: 'Products retrieved successfully.'
    )]
    public function index(): JsonResponse
    {
        return $this->success(
            ProductResource::collection(
                $this->productService->all()
            )
        );
    }

    /**
     * --------------------------------------------------------------------------
     * Get Product
     * --------------------------------------------------------------------------
     * Retrieve a product by its ID.
     */
    #[OA\Get(
        path: '/api/v1/products/{product}',
        summary: 'Get product by ID',
        tags: ['Products']
    )]
    #[OA\Parameter(
        name: 'product',
        in: 'path',
        required: true,
        description: 'Product ID',
        schema: new OA\Schema(
            type: 'integer',
            example: 1
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Product retrieved successfully.'
    )]
    #[OA\Response(
        response: 404,
        description: 'Product not found.'
    )]
    public function show(Product $product): JsonResponse
    {
        return $this->success(
            new ProductResource(
                $this->productService->find($product->id)
            )
        );
    }
    /**
     * --------------------------------------------------------------------------
     * Create Product
     * --------------------------------------------------------------------------
     * Create a new product. (Admin Only)
     */
    #[OA\Post(
        path: '/api/v1/products',
        summary: 'Create product',
        tags: ['Products'],
        security: [['sanctum' => []]]
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: [
                'category_id',
                'name',
                'sku',
                'price',
                'stock',
                'status'
            ],
            properties: [

                new OA\Property(
                    property: 'category_id',
                    type: 'integer',
                    example: 1
                ),

                new OA\Property(
                    property: 'name',
                    type: 'string',
                    example: 'iPhone 16 Pro'
                ),

                new OA\Property(
                    property: 'sku',
                    type: 'string',
                    example: 'IP16PRO-256-BLK'
                ),

                new OA\Property(
                    property: 'description',
                    type: 'string',
                    example: 'Apple iPhone 16 Pro 256GB Black'
                ),

                new OA\Property(
                    property: 'price',
                    type: 'number',
                    format: 'float',
                    example: 1499.99
                ),

                new OA\Property(
                    property: 'stock',
                    type: 'integer',
                    example: 50
                ),

                new OA\Property(
                    property: 'status',
                    type: 'string',
                    example: 'active'
                ),
            ]
        )
    )]
    #[OA\Response(
        response: 201,
        description: 'Product created successfully.'
    )]
    #[OA\Response(
        response: 401,
        description: 'Unauthenticated.'
    )]
    #[OA\Response(
        response: 403,
        description: 'Forbidden. Admin access required.'
    )]
    #[OA\Response(
        response: 422,
        description: 'Validation failed.'
    )]
    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = $this->productService->create(
            $request->validated()
        );

        return $this->success(
            new ProductResource($product),
            'Product created successfully.',
            201
        );
    }
    /**
     * --------------------------------------------------------------------------
     * Update Product
     * --------------------------------------------------------------------------
     * Update an existing product. (Admin Only)
     */
    #[OA\Put(
        path: '/api/v1/products/{product}',
        summary: 'Update product',
        tags: ['Products'],
        security: [['sanctum' => []]]
    )]
    #[OA\Parameter(
        name: 'product',
        in: 'path',
        required: true,
        description: 'Product ID',
        schema: new OA\Schema(
            type: 'integer',
            example: 1
        )
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [

                new OA\Property(
                    property: 'category_id',
                    type: 'integer',
                    example: 1
                ),

                new OA\Property(
                    property: 'name',
                    type: 'string',
                    example: 'iPhone 16 Pro Max'
                ),

                new OA\Property(
                    property: 'sku',
                    type: 'string',
                    example: 'IP16PM-512-BLK'
                ),

                new OA\Property(
                    property: 'description',
                    type: 'string',
                    example: 'Updated Apple iPhone 16 Pro Max 512GB'
                ),

                new OA\Property(
                    property: 'price',
                    type: 'number',
                    format: 'float',
                    example: 1699.99
                ),

                new OA\Property(
                    property: 'stock',
                    type: 'integer',
                    example: 40
                ),

                new OA\Property(
                    property: 'status',
                    type: 'string',
                    enum: ['active', 'inactive'],
                    example: 'active'
                ),
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Product updated successfully.'
    )]
    #[OA\Response(
        response: 401,
        description: 'Unauthenticated.'
    )]
    #[OA\Response(
        response: 403,
        description: 'Forbidden. Admin access required.'
    )]
    #[OA\Response(
        response: 404,
        description: 'Product not found.'
    )]
    #[OA\Response(
        response: 422,
        description: 'Validation failed.'
    )]
    public function update(
        UpdateProductRequest $request,
        Product $product
    ): JsonResponse {

        $product = $this->productService->update(
            $product,
            $request->validated()
        );

        return $this->success(
            new ProductResource($product),
            'Product updated successfully.'
        );
    }

    /**
     * --------------------------------------------------------------------------
     * Delete Product
     * --------------------------------------------------------------------------
     * Delete an existing product. (Admin Only)
     */
    #[OA\Delete(
        path: '/api/v1/products/{product}',
        summary: 'Delete product',
        tags: ['Products'],
        security: [['sanctum' => []]]
    )]
    #[OA\Parameter(
        name: 'product',
        in: 'path',
        required: true,
        description: 'Product ID',
        schema: new OA\Schema(
            type: 'integer',
            example: 1
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Product deleted successfully.'
    )]
    #[OA\Response(
        response: 401,
        description: 'Unauthenticated.'
    )]
    #[OA\Response(
        response: 403,
        description: 'Forbidden. Admin access required.'
    )]
    #[OA\Response(
        response: 404,
        description: 'Product not found.'
    )]
    public function destroy(Product $product): JsonResponse
    {
        $this->productService->delete($product);

        return $this->success(
            null,
            'Product deleted successfully.'
        );
    }
}
