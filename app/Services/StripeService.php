<?php

namespace App\Services;

use App\Models\Order;
use Stripe\PaymentIntent;
use Stripe\StripeClient;

class StripeService
{
    protected StripeClient $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(
            config('services.stripe.secret')
        );
    }

    /**
     * Create a Stripe Payment Intent.
     */
    public function createPaymentIntent(Order $order): PaymentIntent
    {
        return $this->stripe->paymentIntents->create([
            'amount' => (int) round($order->total * 100),
            'currency' => config('services.stripe.currency', 'usd'),

            'automatic_payment_methods' => [
                'enabled' => true,
            ],

            'metadata' => [
                'order_id' => $order->id,
                'user_id' => $order->user_id,
            ],

            'description' => "Order #{$order->id}",
        ]);
    }

    /**
     * Retrieve a Stripe Payment Intent.
     */
    public function retrievePaymentIntent(string $paymentIntentId): PaymentIntent
    {
        return $this->stripe->paymentIntents->retrieve(
            $paymentIntentId
        );
    }
}
