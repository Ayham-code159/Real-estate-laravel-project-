<?php

namespace App\Models;

use App\Models\Message;
use App\Models\Conversation;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    public const PLAN_BASIC = 'basic';
    public const PLAN_PLUS = 'plus';
    public const PLAN_PRO = 'pro';

    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'email',
        'phone',
        'active_business_account_id',
        'password',
        'status',
        'plan',
        'business_account_limit',
        'stripe_customer_id',
        'plan_paid_at',
        'email_verified_at',
        'phone_verified_at',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'plan_paid_at' => 'datetime',
            'business_account_limit' => 'integer',
            'password' => 'hashed',
        ];
    }

    public static function plans(): array
    {
        return [
            self::PLAN_BASIC => [
                'name' => 'Basic',
                'price_usd' => 0,
                'business_account_limit' => 0,
            ],
            self::PLAN_PLUS => [
                'name' => 'Plus',
                'price_usd' => 20,
                'business_account_limit' => 1,
            ],
            self::PLAN_PRO => [
                'name' => 'Pro',
                'price_usd' => 100,
                'business_account_limit' => 5,
            ],
        ];
    }

    public static function planLimit(string $plan): int
    {
        return self::plans()[$plan]['business_account_limit'] ?? 0;
    }

    public function isBasicPlan(): bool
    {
        return $this->plan === self::PLAN_BASIC;
    }

    public function isPlusPlan(): bool
    {
        return $this->plan === self::PLAN_PLUS;
    }

    public function isProPlan(): bool
    {
        return $this->plan === self::PLAN_PRO;
    }

    public function canUpgradeTo(string $plan): bool
    {
        $rank = [
            self::PLAN_BASIC => 0,
            self::PLAN_PLUS => 1,
            self::PLAN_PRO => 2,
        ];

        return ($rank[$plan] ?? -1) > ($rank[$this->plan] ?? 0);
    }

    public function upgradeToPlan(string $plan): void
    {
        $this->update([
            'plan' => $plan,
            'business_account_limit' => self::planLimit($plan),
            'plan_paid_at' => now(),
        ]);
    }

    public function remainingBusinessAccounts(): int
    {
        $used = $this->businessAccounts()->count();

        return max(0, $this->business_account_limit - $used);
    }

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    public function businessAccounts(): HasMany
    {
        return $this->hasMany(BusinessAccount::class);
    }

    public function activeBusinessAccount(): BelongsTo
    {
        return $this->belongsTo(BusinessAccount::class, 'active_business_account_id');
    }

    public function sentListingRequests(): HasMany
    {
        return $this->hasMany(ListingRequest::class, 'buyer_user_id');
    }

    public function receivedListingRequests(): HasMany
    {
        return $this->hasMany(ListingRequest::class, 'seller_user_id');
    }

    public function cancelledListingRequests(): HasMany
    {
        return $this->hasMany(ListingRequest::class, 'cancelled_by_user_id');
    }

    public function listingRequestRatings(): HasMany
    {
        return $this->hasMany(ListingRequestRating::class, 'buyer_user_id');
    }

    public function deviceTokens()
    {
        return $this->hasMany(DeviceToken::class);
    }

    public function appNotifications(): HasMany
    {
        return $this->hasMany(AppNotification::class);
    }

    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function conversationsAsUserOne(): HasMany
    {
        return $this->hasMany(Conversation::class, 'user_one_id');
    }

    public function conversationsAsUserTwo(): HasMany
    {
        return $this->hasMany(Conversation::class, 'user_two_id');
    }
}
