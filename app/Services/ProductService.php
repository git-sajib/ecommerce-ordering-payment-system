<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductService
{
    /**
     * Get all products with category.
     */
    public function all(): Collection
    {
        return Product::with('category')
            ->latest()
            ->get();
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
        return Product::create($data);
    }

    /**
     * Update an existing product.
     */
    public function update(Product $product, array $data): Product
    {
        $product->update($data);

        return $product->fresh();
    }

    /**
     * Delete a product.
     */
    public function delete(Product $product): void
    {
        $product->delete();
    }

    /**
     * Reduce stock after successful payment.
     */
    public function reduceStock(Product $product, int $quantity): void
    {
        $product->decrement('stock', $quantity);
    }
}
