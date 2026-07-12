<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class OrderService
{
    /**
     * Create a new order.
     */
    public function create(User $user, array $items): Order
    {
        return DB::transaction(function () use ($user, $items) {

            // Load all requested products in a single query
            $products = $this->loadProducts($items);

            $subtotal = $this->calculateSubtotal($items, $products);

            $order = Order::create([
                'user_id'  => $user->id,
                'subtotal' => $subtotal,
                'total'    => $subtotal,
                'status'   => OrderStatus::PENDING->value,
            ]);

            $this->createOrderItems($order, $items, $products);

            return $order->load('items.product');
        });
    }

    /**
     * Get all orders for the authenticated user.
     */
    public function all(User $user)
    {
        return Order::with([
            'items.product',
            'payment',
        ])
            ->where('user_id', $user->id)
            ->latest()
            ->get();
    }

    /**
     * Get a single order for the authenticated user.
     */
    public function find(User $user, Order $order): Order
    {
        return Order::with([
            'items.product',
            'payment',
        ])
            ->where('user_id', $user->id)
            ->findOrFail($order->id);
    }

    /**
     * Load all requested products in one query.
     */
    private function loadProducts(array $items): Collection
    {
        $productIds = collect($items)
            ->pluck('product_id')
            ->unique();

        return Product::whereIn('id', $productIds)
            ->get()
            ->keyBy('id');
    }

    /**
     * Calculate order subtotal.
     */
    private function calculateSubtotal(
        array $items,
        Collection $products
    ): float {

        $subtotal = 0;

        foreach ($items as $item) {

            $product = $products->get($item['product_id']);

            if (! $product) {
                abort(404, 'Product not found.');
            }

            if ($product->stock < $item['quantity']) {
                abort(
                    422,
                    "Insufficient stock for {$product->name}"
                );
            }

            $subtotal += $product->price * $item['quantity'];
        }

        return $subtotal;
    }

    /**
     * Create order items.
     */
    private function createOrderItems(
        Order $order,
        array $items,
        Collection $products
    ): void {

        foreach ($items as $item) {

            $product = $products->get($item['product_id']);

            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $product->id,
                'quantity'   => $item['quantity'],
                'price'      => $product->price,
                'subtotal'   => $product->price * $item['quantity'],
            ]);
        }
    }
}
