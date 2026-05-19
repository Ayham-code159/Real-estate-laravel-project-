<?php

namespace App\Http\Controllers\Api\Payment;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Payment\StripePaymentService;

class PaymentController extends Controller
{
    public function __construct(
        private StripePaymentService $stripePaymentService
    ) {}

    public function currentPlan(Request $request): JsonResponse
    {
        return response()->json([
            'data' => $this->stripePaymentService->currentPlan($request->user()),
        ]);
    }

    public function checkout(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'plan' => ['required', 'string', 'in:plus,pro'],
        ]);

        $checkoutUrl = $this->stripePaymentService->createCheckoutSession(
            $request->user(),
            $validated['plan']
        );

        return response()->json([
            'message' => 'Checkout session created successfully.',
            'checkout_url' => $checkoutUrl,
        ]);
    }

    public function success(): JsonResponse
    {
        return response()->json([
            'message' => 'Payment completed successfully. Your plan will be updated after Stripe confirms the payment.',
        ]);
    }

    public function cancel(): JsonResponse
    {
        return response()->json([
            'message' => 'Payment was cancelled.',
        ]);
    }

    public function myPlan(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'data' => [
                'plan' => $user->plan,
                'plan_name' => ucfirst($user->plan),
                'business_account_limit' => $user->business_account_limit,
                'used_business_accounts' => $user->businessAccounts()->count(),
                'remaining_business_accounts' => $user->remainingBusinessAccounts(),
                'plan_paid_at' => $user->plan_paid_at,
                'stripe_customer_id' => $user->stripe_customer_id,
            ],
    ]);
}
}
