<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ServiceListing extends Model implements HasMedia
{
    use InteractsWithMedia;

    public const STATUS_PENDING = 1;
    public const STATUS_APPROVED = 2;
    public const STATUS_REJECTED = 3;

    protected $fillable = [
        'business_account_id',
        'service_id',
        'service_subcategory_id',
        'title',
        'title_en',
        'title_ar',
        'description',
        'description_en',
        'description_ar',
        'mode',
        'price_usd',
        'price_syp',
        'latitude',
        'longitude',
        'location_label',
        'metadata',
        'status',
        'rejection_reason',
    ];

    protected $appends = [
        'status_label',
        'status_badge_class',
        'main_photo_url',
        'main_photo_thumb_url',
        'sub_photo_urls',
        'translated_title',
        'translated_description',
        'google_maps_url',
    ];

    protected function casts(): array
    {
        return [
            'price_usd' => 'decimal:2',
            'price_syp' => 'decimal:2',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'metadata' => 'array',
            'status' => 'integer',
        ];
    }

    public function businessAccount(): BelongsTo
    {
        return $this->belongsTo(BusinessAccount::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(ServiceSubcategory::class, 'service_subcategory_id');
    }

    public function listingRequests(): HasMany
    {
        return $this->hasMany(ListingRequest::class);
    }

    public function listingRequestRatings(): HasMany
    {
        return $this->hasMany(ListingRequestRating::class);
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

    public function getTitleAttribute($value): string
    {
        if (app()->getLocale() === 'ar') {
            return $this->attributes['title_ar']
                ?: $this->attributes['title_en']
                ?: $value
                ?: '';
        }

        return $this->attributes['title_en']
            ?: $this->attributes['title_ar']
            ?: $value
            ?: '';
    }

    public function getDescriptionAttribute($value): ?string
    {
        if (app()->getLocale() === 'ar') {
            return $this->attributes['description_ar']
                ?: $this->attributes['description_en']
                ?: $value;
        }

        return $this->attributes['description_en']
            ?: $this->attributes['description_ar']
            ?: $value;
    }

    public function getTranslatedTitleAttribute(): string
    {
        return $this->title;
    }

    public function getTranslatedDescriptionAttribute(): ?string
    {
        return $this->description;
    }

    public function getGoogleMapsUrlAttribute(): ?string
    {
        if (! $this->latitude || ! $this->longitude) {
            return null;
        }

        return 'https://www.google.com/maps?q=' . $this->latitude . ',' . $this->longitude;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('main_photo')->singleFile();
        $this->addMediaCollection('sub_photos');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(300)
            ->height(300)
            ->performOnCollections('main_photo', 'sub_photos');
    }

    public function getMainPhotoUrlAttribute(): ?string
    {
        $media = $this->getFirstMedia('main_photo');

        return $media ? $media->getUrl() : null;
    }

    public function getMainPhotoThumbUrlAttribute(): ?string
    {
        $media = $this->getFirstMedia('main_photo');

        return $media ? $media->getUrl('thumb') : null;
    }

    public function getSubPhotoUrlsAttribute(): array
    {
        return $this->getMedia('sub_photos')
            ->map(function (Media $media) {
                return [
                    'id' => $media->id,
                    'name' => $media->name,
                    'url' => $media->getUrl(),
                    'thumb_url' => $media->getUrl('thumb'),
                ];
            })
            ->values()
            ->toArray();
    }
}
