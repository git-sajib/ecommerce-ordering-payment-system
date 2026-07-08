<?php

namespace App\Strategies\Payment;

use App\Models\Order;
use Illuminate\Support\Str;

class BkashPaymentStrategy implements PaymentStrategy
{
    public function pay(Order $order): array
    {
        return [
            'success' => true,
            'transaction_id' => 'BKASH-' . Str::upper(Str::random(12)),
            'raw_response' => [
                'gateway' => 'bKash',
                'message' => 'Payment processed successfully.',
            ],
        ];
    }
}
