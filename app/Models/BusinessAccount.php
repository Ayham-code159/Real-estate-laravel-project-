<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusinessAccount extends Model
{
    public const STATUS_PENDING = 1;
    public const STATUS_APPROVED = 2;
    public const STATUS_REJECTED = 3;

    protected $fillable = [
        'user_id',
        'business_type_id',
        'city_id',
        'business_name',
        'business_name_en',
        'business_name_ar',
        'latitude',
        'longitude',
        'location_label',
        'status',
        'rejection_reason',
    ];

    protected $appends = [
        'translated_business_name',
        'google_maps_url',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'integer',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function businessType(): BelongsTo
    {
        return $this->belongsTo(BusinessType::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function serviceListings(): HasMany
    {
        return $this->hasMany(ServiceListing::class);
    }

    public function buyerListingRequests(): HasMany
    {
        return $this->hasMany(ListingRequest::class, 'buyer_business_account_id');
    }

    public function sellerListingRequests(): HasMany
    {
        return $this->hasMany(ListingRequest::class, 'seller_business_account_id');
    }

    public static function statuses(): array
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_REJECTED => 'Rejected',
        ];
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function getStatusLabelAttribute(): string
    {
        return self::statuses()[$this->status] ?? 'Unknown';
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'badge-warning',
            self::STATUS_APPROVED => 'badge-success',
            self::STATUS_REJECTED => 'badge-danger',
            default => 'badge-primary',
        };
    }

    public function getBusinessNameAttribute($value): string
    {
        if (app()->getLocale() === 'ar') {
            return $this->attributes['business_name_ar']
                ?: $this->attributes['business_name_en']
                ?: $value
                ?: '';
        }

        return $this->attributes['business_name_en']
            ?: $this->attributes['business_name_ar']
            ?: $value
            ?: '';
    }

    public function getTranslatedBusinessNameAttribute(): string
    {
        return $this->business_name;
    }

    public function getGoogleMapsUrlAttribute(): ?string
    {
        if (! $this->latitude || ! $this->longitude) {
            return null;
        }

        return 'https://www.google.com/maps?q=' . $this->latitude . ',' . $this->longitude;
    }
}
