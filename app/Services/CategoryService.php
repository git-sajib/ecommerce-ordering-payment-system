<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection as SupportCollection;

class CategoryService
{
    private const CACHE_CATEGORIES = 'categories';

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
        return Cache::remember(
            self::CACHE_CATEGORIES,
            now()->addHour(),
            fn() => Category::latest()->get()
        );
    }

    /**
     * Get category tree.
     *
     * Builds the hierarchy in memory using a single query.
     */
    public function tree(): Collection
    {
        $categories = Category::query()
            ->orderBy('name')
            ->get();

        return $this->buildTree($categories);
    }

    /**
     * Get categories in DFS order using the cached adjacency list.
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

        $models = Category::query()
            ->whereIn('id', $ids)
            ->orderBy('name')
            ->get();

        $indexed = $models->keyBy('id');

        return new Collection(
            collect($ids)
                ->map(fn(int $id) => $indexed->get($id))
                ->filter()
                ->values()
                ->all()
        );
    }

    /**
     * Build an unlimited-depth category tree in memory.
     *
     * Executes only one database query.
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
     * Attach child categories recursively.
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
        Cache::forget(self::CACHE_CATEGORIES);

        $this->categoryCacheService->forget();
    }
}
