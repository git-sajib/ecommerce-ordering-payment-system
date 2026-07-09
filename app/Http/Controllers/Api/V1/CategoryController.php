<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Services\CategoryService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected CategoryService $categoryService
    ) {}

    public function index(): JsonResponse
    {
        return $this->success(
            CategoryResource::collection(
                $this->categoryService->all()
            )
        );
    }

    public function tree(): JsonResponse
    {
        return $this->success(
            CategoryResource::collection(
                $this->categoryService->tree()
            )
        );
    }

    public function dfs(): JsonResponse
    {
        return $this->success(
            $this->categoryService->dfs()
        );
    }

    public function show(Category $category): JsonResponse
    {
        return $this->success(
            new CategoryResource(
                $category->load('children')
            )
        );
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $category = $this->categoryService->create(
            $request->validated()
        );

        return $this->success(
            new CategoryResource($category),
            'Category created successfully.',
            201
        );
    }

    public function update(
        UpdateCategoryRequest $request,
        Category $category
    ): JsonResponse {

        $category = $this->categoryService->update(
            $category,
            $request->validated()
        );

        return $this->success(
            new CategoryResource($category),
            'Category updated successfully.'
        );
    }

    public function destroy(Category $category): JsonResponse
    {
        $this->categoryService->delete($category);

        return $this->success(
            null,
            'Category deleted successfully.'
        );
    }
}
