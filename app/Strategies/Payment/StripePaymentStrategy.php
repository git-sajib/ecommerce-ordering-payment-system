<?php

namespace App\Strategies\Payment;

use App\Models\Order;
use App\Services\StripeService;

class StripePaymentStrategy implements PaymentStrategy
{
    public function __construct(
        protected StripeService $stripeService
    ) {}

    /**
     * Create a Stripe Payment Intent.
     */
    public function pay(Order $order): array
    {
        $paymentIntent = $this->stripeService
            ->createPaymentIntent($order);

        return [
            'transaction_id' => $paymentIntent->id,

            'client_secret' => $paymentIntent->client_secret,

            'checkout_url' => null,

            'status' => $paymentIntent->status,

            'raw_response' => $paymentIntent->toArray(),
        ];
    }
}
