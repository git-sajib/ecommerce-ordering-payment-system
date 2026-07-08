<?php

namespace App\Strategies\Payment;

use App\Models\Order;
use Illuminate\Support\Str;

class StripePaymentStrategy implements PaymentStrategy
{
    public function pay(Order $order): array
    {
        return [
            'success' => true,
            'transaction_id' => 'STRIPE-' . Str::upper(Str::random(12)),
            'raw_response' => [
                'gateway' => 'Stripe',
                'message' => 'Payment processed successfully.',
            ],
        ];
    }
}
