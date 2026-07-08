<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Collection;

class CategoryTreeService
{
    /**
     * Build a hierarchical category tree.
     */
    public function buildTree(?int $parentId = null): Collection
    {
        $categories = Category::where('parent_id', $parentId)
            ->orderBy('name')
            ->get();

        return $categories->map(function (Category $category) {
            $category->children = $this->buildTree($category->id);

            return $category;
        });
    }

    /**
     * Depth-First Search traversal.
     */
    public function depthFirstTraversal(Collection $categories, array &$result = []): array
    {
        foreach ($categories as $category) {
            $result[] = $category;

            if ($category->children->isNotEmpty()) {
                $this->depthFirstTraversal($category->children, $result);
            }
        }

        return $result;
    }
}
