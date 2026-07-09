<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class CategoryService
{
    public function __construct(
        protected CategoryTreeService $treeService
    ) {}

    /**
     * Create category.
     */
    public function create(array $data): Category
    {
        return Category::create($data);
    }

    /**
     * Update category.
     */
    public function update(Category $category, array $data): Category
    {
        $category->update($data);

        return $category->fresh();
    }

    /**
     * Delete category.
     */
    public function delete(Category $category): void
    {
        $category->delete();
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
        return Category::latest()->get();
    }

    /**
     * Category tree.
     */
    public function tree(): Collection
    {
        return $this->treeService->buildTree();
    }

    /**
     * DFS traversal.
     */
    public function dfs(): array
    {
        $tree = $this->tree();

        return $this->treeService->depthFirstTraversal($tree);
    }
}
