<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class CategoryCacheService
{
    private const CACHE_CATEGORIES = 'categories.data';

    private const CACHE_ADJACENCY = 'categories.adjacency';

    private const TTL = 3600;

    /**
     * Get cached categories.
     */
    public function getCategories(): Collection
    {
        return Category::hydrate(
            Cache::remember(
                self::CACHE_CATEGORIES,
                self::TTL,
                fn() => Category::query()
                    ->select([
                        'id',
                        'parent_id',
                        'name',
                        'slug',
                        'status',
                        'created_at',
                        'updated_at',
                    ])
                    ->orderBy('name')
                    ->get()
                    ->toArray()
            )
        );
    }

    /**
     * Get cached adjacency list.
     */
    public function getAdjacencyList(): array
    {
        return Cache::remember(
            self::CACHE_ADJACENCY,
            self::TTL,
            fn() => $this->buildAdjacencyList()
        );
    }

    /**
     * Clear cache.
     */
    public function forget(): void
    {
        Cache::forget(self::CACHE_CATEGORIES);
        Cache::forget(self::CACHE_ADJACENCY);
    }

    /**
     * Build adjacency list.
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
