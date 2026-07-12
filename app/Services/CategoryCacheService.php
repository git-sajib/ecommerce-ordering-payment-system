<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;

class CategoryCacheService
{
    /**
     * Redis cache key.
     */
    private const CACHE_KEY = 'categories.adjacency';

    /**
     * Cache lifetime.
     */
    private const TTL = 3600;

    /**
     * Get cached adjacency list.
     */
    public function getAdjacencyList(): array
    {
        return Cache::remember(
            self::CACHE_KEY,
            self::TTL,
            fn() => $this->buildAdjacencyList()
        );
    }

    /**
     * Remove cached adjacency list.
     */
    public function forget(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Build adjacency list from database.
     *
     * Result format:
     *
     * [
     *     null => [1, 8],
     *     1 => [2, 3],
     *     2 => [4],
     *     3 => [],
     * ]
     */
    private function buildAdjacencyList(): array
    {
        $adjacency = [];

        $categories = Category::query()
            ->select([
                'id',
                'parent_id',
            ])
            ->orderBy('parent_id')
            ->orderBy('id')
            ->get();

        foreach ($categories as $category) {

            $parentId = $category->parent_id;

            $adjacency[$parentId] ??= [];

            $adjacency[$parentId][] = $category->id;

            $adjacency[$category->id] ??= [];
        }

        return $adjacency;
    }
}
