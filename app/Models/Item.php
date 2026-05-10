<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Item extends Model implements HasMedia
{
    use InteractsWithMedia;

    public const STATUS_PENDING = 1;
    public const STATUS_APPROVED = 2;
    public const STATUS_REJECTED = 3;

    public const TYPE_SELL = 'sell';
    public const TYPE_RENT = 'rent';

    protected $fillable = [
        'business_account_id',
        'category_id',
        'subcategory_id',

        'title',
        'title_en',
        'title_ar',

        'description',
        'description_en',
        'description_ar',

        'item_type',

        'price_usd',
        'price_syp',

        'lat',
        'lng',
        'location_label',

        'dynamic_values',

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
            'lat' => 'decimal:7',
            'lng' => 'decimal:7',
            'dynamic_values' => 'array',
            'status' => 'integer',
        ];
    }

    public function businessAccount(): BelongsTo
    {
        return $this->belongsTo(BusinessAccount::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class);
    }

    public static function statuses(): array
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_REJECTED => 'Rejected',
        ];
    }

    public static function itemTypes(): array
    {
        return [
            self::TYPE_SELL,
            self::TYPE_RENT,
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

    public function getGoogleMapsUrlAttribute(): ?string
    {
        if (! $this->lat || ! $this->lng) {
            return null;
        }

        return 'https://www.google.com/maps?q=' . $this->lat . ',' . $this->lng;
    }
}
