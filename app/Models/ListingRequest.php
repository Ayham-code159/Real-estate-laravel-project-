<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ListingRequest extends Model
{
    public const STATUS_PENDING = 1;
    public const STATUS_ACCEPTED = 2;
    public const STATUS_COMPLETED = 3;
    public const STATUS_REJECTED = 4;
    public const STATUS_CANCELLED = 5;

    protected $fillable = [
        'service_listing_id',
        'buyer_user_id',
        'buyer_business_account_id',
        'seller_user_id',
        'seller_business_account_id',
        'requested_for',
        'description',
        'request_metadata',
        'price_usd_snapshot',
        'price_syp_snapshot',
        'status',
        'seller_response_note',
        'accepted_at',
        'rejected_at',
        'completed_at',
        'cancelled_at',
        'cancelled_by_user_id',
        'cancellation_reason',
    ];

    protected $appends = [
        'status_label',
        'status_badge_class',
    ];

    protected function casts(): array
    {
        return [
            'requested_for' => 'datetime',
            'request_metadata' => 'array',
            'price_usd_snapshot' => 'decimal:2',
            'price_syp_snapshot' => 'decimal:2',
            'status' => 'integer',
            'accepted_at' => 'datetime',
            'rejected_at' => 'datetime',
            'completed_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    public function serviceListing(): BelongsTo
    {
        return $this->belongsTo(ServiceListing::class);
    }

    public function buyerUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_user_id');
    }

    public function buyerBusinessAccount(): BelongsTo
    {
        return $this->belongsTo(BusinessAccount::class, 'buyer_business_account_id');
    }

    public function sellerUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_user_id');
    }

    public function sellerBusinessAccount(): BelongsTo
    {
        return $this->belongsTo(BusinessAccount::class, 'seller_business_account_id');
    }

    public function cancelledByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cancelled_by_user_id');
    }

    public function rating(): HasOne
    {
        return $this->hasOne(ListingRequestRating::class, 'listing_request_id');
    }

    public static function statuses(): array
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_ACCEPTED => 'Accepted',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_REJECTED => 'Rejected',
            self::STATUS_CANCELLED => 'Cancelled',
        ];
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isAccepted(): bool
    {
        return $this->status === self::STATUS_ACCEPTED;
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function getStatusLabelAttribute(): string
    {
        return self::statuses()[$this->status] ?? 'Unknown';
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'badge-warning',
            self::STATUS_ACCEPTED => 'badge-primary',
            self::STATUS_COMPLETED => 'badge-success',
            self::STATUS_REJECTED => 'badge-danger',
            self::STATUS_CANCELLED => 'badge-danger',
            default => 'badge-primary',
        };
    }
}
