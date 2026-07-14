<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

class CategoryService
{
    public function __construct(
        protected CategoryCacheService $categoryCacheService,
        protected CategoryTraversalService $categoryTraversalService
    ) {}

    /**
     * Create category.
     */
    public function create(array $data): Category
    {
        $category = Category::create($data);

        $this->clearCache();

        return $category;
    }

    /**
     * Update category.
     */
    public function update(Category $category, array $data): Category
    {
        $category->update($data);

        $this->clearCache();

        return $category->fresh();
    }

    /**
     * Delete category.
     */
    public function delete(Category $category): void
    {
        $category->delete();

        $this->clearCache();
    }

    /**
     * Find category.
     */
    public function find(int $id): Category
    {
        return Category::findOrFail($id);
    }

    /**
     * Get all categories.
     */
    public function all(): Collection
    {
        return $this->categoryCacheService->getCategories();
    }

    /**
     * Get category tree.
     */
    public function tree(): Collection
    {
        return $this->buildTree(
            $this->categoryCacheService->getCategories()
        );
    }

    /**
     * Get categories in DFS order.
     */
    public function dfs(): Collection
    {
        $adjacency = $this->categoryCacheService->getAdjacencyList();

        $ids = $this->categoryTraversalService->depthFirstIds(
            $adjacency
        );

        if (empty($ids)) {
            return new Collection();
        }

        $models = $this->categoryCacheService
            ->getCategories()
            ->keyBy('id');

        return new Collection(
            collect($ids)
                ->map(fn(int $id) => $models->get($id))
                ->filter()
                ->values()
                ->all()
        );
    }

    /**
     * Build unlimited-depth category tree.
     */
    private function buildTree(Collection $categories): Collection
    {
        $grouped = $categories->groupBy('parent_id');

        return $this->attachChildren(
            null,
            $grouped
        );
    }

    /**
     * Attach children recursively.
     */
    private function attachChildren(
        ?int $parentId,
        SupportCollection $grouped
    ): Collection {

        $children = new Collection(
            $grouped->get($parentId, collect())->all()
        );

        $children->each(function (Category $category) use ($grouped) {

            $category->setRelation(
                'children',
                $this->attachChildren(
                    $category->id,
                    $grouped
                )
            );
        });

        return $children;
    }

    /**
     * Clear category caches.
     */
    private function clearCache(): void
    {
        $this->categoryCacheService->forget();
    }
}
