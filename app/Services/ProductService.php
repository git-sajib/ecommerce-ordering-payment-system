<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class ProductService
{
    private const CACHE_PRODUCTS = 'products';

    /**
     * Get all products with category.
     */
    public function all(): Collection
    {
        return Cache::remember(
            self::CACHE_PRODUCTS,
            now()->addHour(),
            fn() => Product::with('category')
                ->latest()
                ->get()
        );
    }

    /**
     * Find a product by ID.
     */
    public function find(int $id): Product
    {
        return Product::with('category')
            ->findOrFail($id);
    }

    /**
     * Create a new product.
     */
    public function create(array $data): Product
    {
        $product = Product::create($data);

        $this->clearCache();

        return $product;
    }

    /**
     * Update an existing product.
     */
    public function update(Product $product, array $data): Product
    {
        $product->update($data);

        $this->clearCache();

        return $product->fresh();
    }

    /**
     * Delete a product.
     */
    public function delete(Product $product): void
    {
        $product->delete();

        $this->clearCache();
    }

    /**
     * Reduce stock after successful payment.
     */
    public function reduceStock(Product $product, int $quantity): void
    {
        if ($product->stock < $quantity) {
            abort(422, "Insufficient stock for {$product->name}");
        }

        $product->decrement('stock', $quantity);

        $this->clearCache();
    }

    /**
     * Clear product cache.
     */
    private function clearCache(): void
    {
        Cache::forget(self::CACHE_PRODUCTS);
    }
}
