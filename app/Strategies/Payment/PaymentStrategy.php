<?php

namespace App\Strategies\Payment;

use App\Models\Order;

interface PaymentStrategy
{
    /**
     * Process a payment.
     *
     * @return array{
     *     success: bool,
     *     transaction_id: string,
     *     raw_response: array
     * }
     */
    public function pay(Order $order): array;
}
