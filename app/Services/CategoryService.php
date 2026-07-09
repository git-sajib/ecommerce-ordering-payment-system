<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class CategoryService
{
    private const CACHE_CATEGORIES = 'categories';
    private const CACHE_TREE = 'categories.tree';
    private const CACHE_DFS = 'categories.dfs';

    public function __construct(
        protected CategoryTreeService $treeService
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
     * List all categories.
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
     */
    public function tree(): Collection
    {
        return Cache::remember(
            self::CACHE_TREE,
            now()->addHour(),
            fn() => $this->treeService->buildTree()
        );
    }

    /**
     * Get categories using DFS traversal.
     */
    public function dfs(): array
    {
        return Cache::remember(
            self::CACHE_DFS,
            now()->addHour(),
            function () {

                $tree = $this->tree();

                return $this->treeService->depthFirstTraversal($tree);
            }
        );
    }

    /**
     * Clear category cache.
     */
    private function clearCache(): void
    {
        Cache::forget(self::CACHE_CATEGORIES);
        Cache::forget(self::CACHE_TREE);
        Cache::forget(self::CACHE_DFS);
    }
}
