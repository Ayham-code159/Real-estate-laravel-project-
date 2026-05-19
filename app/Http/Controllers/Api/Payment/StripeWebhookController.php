<?php

namespace App\Http\Controllers\Api\Payment;

use Stripe\Webhook;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Payment\StripePaymentService;

class StripeWebhookController extends Controller
{
    public function __construct(
        private StripePaymentService $stripePaymentService
    ) {}

    public function handle(Request $request): JsonResponse
    {
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');
        $webhookSecret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent(
                $payload,
                $signature,
                $webhookSecret
            );
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Invalid Stripe webhook.',
                'error' => $e->getMessage(),
            ], 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;

            $metadata = $session->metadata?->toArray() ?? [];

            $this->stripePaymentService->applySuccessfulPayment(
                $metadata,
                $session->customer ?? null
            );
        }

        return response()->json([
            'message' => 'Webhook handled successfully.',
        ]);
    }
}
