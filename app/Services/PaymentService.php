<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Factories\PaymentStrategyFactory;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    public function __construct(
        protected ProductService $productService
    ) {}

    /**
     * Process payment for an order.
     */
    public function pay(Order $order, string $provider): Payment
    {
        // Prevent duplicate payments
        if ($order->payment()->exists()) {
            abort(422, 'Payment already exists for this order.');
        }

        // Only pending orders can be paid
        if ($order->status !== OrderStatus::PENDING->value) {
            abort(422, 'This order has already been paid.');
        }

        return DB::transaction(function () use ($order, $provider) {

            // Resolve payment strategy
            $strategy = PaymentStrategyFactory::make($provider);

            // Simulate payment
            $result = $strategy->pay($order);

            // Store payment record
            $payment = Payment::create([
                'order_id'       => $order->id,
                'provider'       => $provider,
                'amount'         => $order->total,
                'transaction_id' => $result['transaction_id'],
                'status'         => $result['success']
                    ? PaymentStatus::SUCCESS->value
                    : PaymentStatus::FAILED->value,
                'raw_response'   => $result['raw_response'],
            ]);

            // Update order and reduce stock only after successful payment
            if ($result['success']) {

                $order->loadMissing('items.product');

                $order->update([
                    'status' => OrderStatus::PAID->value,
                ]);

                foreach ($order->items as $item) {

                    $this->productService->reduceStock(
                        $item->product,
                        $item->quantity
                    );
                }
            }

            return $payment;
        });
    }
}
