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
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Categories',
    description: 'Category Management APIs'
)]
class CategoryController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected CategoryService $categoryService
    ) {}

    /**
     * --------------------------------------------------------------------------
     * Get All Categories
     * --------------------------------------------------------------------------
     * Retrieve all available categories.
     */
    #[OA\Get(
        path: '/api/v1/categories',
        summary: 'Get all categories',
        tags: ['Categories']
    )]
    #[OA\Response(
        response: 200,
        description: 'Categories retrieved successfully.'
    )]
    public function index(): JsonResponse
    {
        return $this->success(
            CategoryResource::collection(
                $this->categoryService->all()
            )
        );
    }

    /**
     * --------------------------------------------------------------------------
     * Category Tree
     * --------------------------------------------------------------------------
     * Retrieve categories as a hierarchical tree.
     */
    #[OA\Get(
        path: '/api/v1/categories/tree',
        summary: 'Get category tree',
        tags: ['Categories']
    )]
    #[OA\Response(
        response: 200,
        description: 'Category tree retrieved successfully.'
    )]
    public function tree(): JsonResponse
    {
        return $this->success(
            CategoryResource::collection(
                $this->categoryService->tree()
            )
        );
    }

    /**
     * --------------------------------------------------------------------------
     * DFS Traversal
     * --------------------------------------------------------------------------
     * Retrieve categories using Depth First Search traversal.
     */
    #[OA\Get(
        path: '/api/v1/categories/dfs',
        summary: 'Get DFS traversal',
        tags: ['Categories']
    )]
    #[OA\Response(
        response: 200,
        description: 'DFS traversal retrieved successfully.'
    )]
    public function dfs(): JsonResponse
    {
        return $this->success(
            $this->categoryService->dfs()
        );
    }

    /**
     * --------------------------------------------------------------------------
     * Get Category
     * --------------------------------------------------------------------------
     * Retrieve a single category by its ID.
     */
    #[OA\Get(
        path: '/api/v1/categories/{category}',
        summary: 'Get category by ID',
        tags: ['Categories']
    )]
    #[OA\Parameter(
        name: 'category',
        description: 'Category ID',
        in: 'path',
        required: true,
        schema: new OA\Schema(
            type: 'integer',
            example: 1
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Category retrieved successfully.'
    )]
    #[OA\Response(
        response: 404,
        description: 'Category not found.'
    )]
    public function show(Category $category): JsonResponse
    {
        return $this->success(
            new CategoryResource(
                $category->load('children')
            )
        );
    }

    /**
     * --------------------------------------------------------------------------
     * Create Category
     * --------------------------------------------------------------------------
     * Create a new category. (Admin Only)
     */
    #[OA\Post(
        path: '/api/v1/categories',
        summary: 'Create category',
        tags: ['Categories'],
        security: [['sanctum' => []]]
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['name'],
            properties: [

                new OA\Property(
                    property: 'name',
                    type: 'string',
                    example: 'Electronics'
                ),

                new OA\Property(
                    property: 'parent_id',
                    type: 'integer',
                    nullable: true,
                    example: null
                )
            ]
        )
    )]
    #[OA\Response(
        response: 201,
        description: 'Category created successfully.'
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
    /**
     * --------------------------------------------------------------------------
     * Update Category
     * --------------------------------------------------------------------------
     * Update an existing category. (Admin Only)
     */
    #[OA\Put(
        path: '/api/v1/categories/{category}',
        summary: 'Update category',
        tags: ['Categories'],
        security: [['sanctum' => []]]
    )]
    #[OA\Parameter(
        name: 'category',
        description: 'Category ID',
        in: 'path',
        required: true,
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
                    property: 'name',
                    type: 'string',
                    example: 'Updated Electronics'
                ),

                new OA\Property(
                    property: 'parent_id',
                    type: 'integer',
                    nullable: true,
                    example: null
                )
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Category updated successfully.'
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
        description: 'Category not found.'
    )]
    #[OA\Response(
        response: 422,
        description: 'Validation failed.'
    )]
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

    /**
     * --------------------------------------------------------------------------
     * Delete Category
     * --------------------------------------------------------------------------
     * Delete an existing category. (Admin Only)
     */
    #[OA\Delete(
        path: '/api/v1/categories/{category}',
        summary: 'Delete category',
        tags: ['Categories'],
        security: [['sanctum' => []]]
    )]
    #[OA\Parameter(
        name: 'category',
        description: 'Category ID',
        in: 'path',
        required: true,
        schema: new OA\Schema(
            type: 'integer',
            example: 1
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Category deleted successfully.'
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
        description: 'Category not found.'
    )]
    public function destroy(Category $category): JsonResponse
    {
        $this->categoryService->delete($category);

        return $this->success(
            null,
            'Category deleted successfully.'
        );
    }
}
