<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;
use Symfony\Component\HttpFoundation\Response;
use UnexpectedValueException;

class StripeWebhookController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService
    ) {}

    /**
     * Handle incoming Stripe webhooks.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $payload = $request->getContent();

        $signature = $request->header('Stripe-Signature');

        try {

            $event = Webhook::constructEvent(
                $payload,
                $signature,
                config('services.stripe.webhook_secret')
            );
        } catch (UnexpectedValueException | SignatureVerificationException $e) {

            return response()->json([
                'success' => false,
                'message' => 'Invalid Stripe webhook.',
            ], Response::HTTP_BAD_REQUEST);
        }

        switch ($event->type) {

            case 'payment_intent.succeeded':

                $this->handleSucceededPayment(
                    $event->data->object->id
                );

                break;

            case 'payment_intent.payment_failed':

                $this->handleFailedPayment(
                    $event->data->object->id
                );

                break;
        }

        return response()->json([
            'success' => true,
            'message' => 'Webhook processed successfully.',
        ]);
    }

    /**
     * Handle successful payment.
     */
    private function handleSucceededPayment(string $transactionId): void
    {
        $payment = Payment::where(
            'transaction_id',
            $transactionId
        )->first();

        if (! $payment) {

            Log::warning(
                'Stripe webhook received for unknown PaymentIntent.',
                [
                    'payment_intent' => $transactionId,
                ]
            );

            return;
        }

        $this->paymentService->completePayment($payment);
    }

    /**
     * Handle failed payment.
     */
    private function handleFailedPayment(string $transactionId): void
    {
        $payment = Payment::where(
            'transaction_id',
            $transactionId
        )->first();

        if (! $payment) {

            Log::warning(
                'Stripe webhook received for unknown PaymentIntent.',
                [
                    'payment_intent' => $transactionId,
                ]
            );

            return;
        }

        $this->paymentService->failPayment($payment);
    }
}
