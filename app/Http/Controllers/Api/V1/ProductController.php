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

class ProductController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected ProductService $productService
    ) {}

    public function index(): JsonResponse
    {
        return $this->success(
            ProductResource::collection(
                $this->productService->all()
            )
        );
    }

    public function show(Product $product): JsonResponse
    {
        return $this->success(
            new ProductResource(
                $this->productService->find($product->id)
            )
        );
    }

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

    public function destroy(Product $product): JsonResponse
    {
        $this->productService->delete($product);

        return $this->success(
            null,
            'Product deleted successfully.'
        );
    }
}
