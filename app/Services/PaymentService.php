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
     * Create a payment.
     */
    public function pay(Order $order, string $provider): array
    {
        if (
            $order->payment()
            ->where('status', PaymentStatus::SUCCESS)
            ->exists()
        ) {
            abort(422, 'This order has already been paid.');
        }

        if ($order->status !== OrderStatus::PENDING->value) {
            abort(422, 'This order cannot be paid.');
        }

        return DB::transaction(function () use ($order, $provider) {

            $strategy = PaymentStrategyFactory::make($provider);

            $gateway = $strategy->pay($order);

            $payment = Payment::create([
                'order_id'       => $order->id,
                'provider'       => $provider,
                'amount'         => $order->total,
                'transaction_id' => $gateway['transaction_id'],
                'status'         => PaymentStatus::PENDING,
                'raw_response'   => $gateway['raw_response'],
            ]);

            return [
                'payment' => $payment,
                'gateway' => $gateway,
            ];
        });
    }

    /**
     * Complete payment.
     */
    public function completePayment(Payment $payment): void
    {
        DB::transaction(function () use ($payment) {

            if ($payment->status === PaymentStatus::SUCCESS) {
                return;
            }

            $payment->update([
                'status' => PaymentStatus::SUCCESS,
            ]);

            $order = $payment->order;

            if ($order->status !== OrderStatus::PAID->value) {

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
        });
    }

    /**
     * Mark payment as failed.
     */
    public function failPayment(Payment $payment): void
    {
        $payment->update([
            'status' => PaymentStatus::FAILED,
        ]);
    }

    /**
     * Mark payment as cancelled.
     */
    public function cancelPayment(Payment $payment): void
    {
        $payment->update([
            'status' => PaymentStatus::CANCELLED,
        ]);
    }
}
