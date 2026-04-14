<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceSubcategory extends Model
{
    protected $fillable = [
        'service_id',
        'name',
        'name_en',
        'name_ar',
    ];

    protected $appends = [
        'translated_name',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function serviceListings(): HasMany
    {
        return $this->hasMany(ServiceListing::class, 'service_subcategory_id');
    }

    public function getNameAttribute($value): string
    {
        if (app()->getLocale() === 'ar') {
            return $this->attributes['name_ar']
                ?? $this->attributes['name_en']
                ?? $value
                ?? '';
        }

        return $this->attributes['name_en']
            ?? $this->attributes['name_ar']
            ?? $value
            ?? '';
    }

    public function getTranslatedNameAttribute(): string
    {
        return $this->name;
    }
}
