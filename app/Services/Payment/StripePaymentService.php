<?php

namespace App\Services\Payment;

use App\Models\User;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Validation\ValidationException;

class StripePaymentService
{
    public function currentPlan(User $user): array
    {
        $usedBusinessAccounts = $user->businessAccounts()->count();

        return [
            'plan' => $user->plan,
            'plan_name' => $this->getPlan($user->plan)['name'],
            'business_account_limit' => $user->business_account_limit,
            'used_business_accounts' => $usedBusinessAccounts,
            'remaining_business_accounts' => max(0, $user->business_account_limit - $usedBusinessAccounts),
            'plan_paid_at' => $user->plan_paid_at,
            'available_plans' => config('payment_plans.plans'),
        ];
    }

    public function createCheckoutSession(User $user, string $planKey): string
    {
        $plan = $this->getPlan($planKey);

        if ($planKey === User::PLAN_BASIC) {
            throw ValidationException::withMessages([
                'plan' => ['Basic plan is free and does not require payment.'],
            ]);
        }

        if (! $user->canUpgradeTo($planKey)) {
            throw ValidationException::withMessages([
                'plan' => ['You can only upgrade to a higher plan.'],
            ]);
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::create([
            'customer_email' => $user->email,
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => $plan['name'] . ' Plan',
                            'description' => $plan['description'],
                        ],
                        'unit_amount' => $plan['price_cents'],
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => config('app.url') . '/payment/success?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => config('app.url') . '/payment/cancel',
            'metadata' => [
                'user_id' => (string) $user->id,
                'plan' => $planKey,
                'business_account_limit' => (string) $plan['business_account_limit'],
            ],
        ]);

        return $session->url;
    }

    public function applySuccessfulPayment(array $metadata, ?string $stripeCustomerId = null): User
    {
        $userId = $metadata['user_id'] ?? null;
        $planKey = $metadata['plan'] ?? null;

        if (! $userId || ! $planKey) {
            throw ValidationException::withMessages([
                'payment' => ['Missing payment metadata.'],
            ]);
        }

        $user = User::query()->findOrFail((int) $userId);
        $plan = $this->getPlan($planKey);

        if (! $user->canUpgradeTo($planKey) && $user->plan !== $planKey) {
            throw ValidationException::withMessages([
                'plan' => ['Invalid plan upgrade.'],
            ]);
        }

        $user->update([
            'plan' => $planKey,
            'business_account_limit' => $plan['business_account_limit'],
            'stripe_customer_id' => $stripeCustomerId ?: $user->stripe_customer_id,
            'plan_paid_at' => now(),
        ]);

        return $user->fresh();
    }

    public function getPlan(string $planKey): array
    {
        $plans = config('payment_plans.plans');

        if (! isset($plans[$planKey])) {
            throw ValidationException::withMessages([
                'plan' => ['Selected plan is invalid.'],
            ]);
        }

        return $plans[$planKey];
    }
}
