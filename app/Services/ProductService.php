<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductService
{
    /**
     * Get all active products with category.
     */
    public function all(): Collection
    {
        return Product::query()
            ->with('category')
            ->where('status', true)
            ->latest()
            ->get();
    }

    /**
     * Find a product by ID.
     */
    public function find(int $id): Product
    {
        return Product::with('category')
            ->where('status', true)
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
        if ($product->stock < $quantity) {
            abort(422, "Insufficient stock for {$product->name}");
        }

        $product->decrement('stock', $quantity);
    }
}
