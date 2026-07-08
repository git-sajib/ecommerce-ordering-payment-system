<?php

namespace App\Factories;

use App\Enums\PaymentProvider;
use App\Strategies\Payment\BkashPaymentStrategy;
use App\Strategies\Payment\PaymentStrategy;
use App\Strategies\Payment\StripePaymentStrategy;
use InvalidArgumentException;

class PaymentStrategyFactory
{
    public static function make(string $provider): PaymentStrategy
    {
        return match ($provider) {

            PaymentProvider::STRIPE->value =>
            new StripePaymentStrategy(),

            PaymentProvider::BKASH->value =>
            new BkashPaymentStrategy(),

            default =>
            throw new InvalidArgumentException(
                'Unsupported payment provider.'
            ),
        };
    }
}
