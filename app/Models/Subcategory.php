<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subcategory extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'name_en',
        'name_ar',
        'description',
        'description_en',
        'description_ar',
        'is_active',
    ];

    protected $appends = [
        'translated_name',
        'translated_description',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function fields(): HasMany
    {
        return $this->hasMany(SubcategoryField::class);
    }

    public function getNameAttribute($value): string
    {
        if (app()->getLocale() === 'ar') {
            return $this->attributes['name_ar']
                ?: $this->attributes['name_en']
                ?: $value
                ?: '';
        }

        return $this->attributes['name_en']
            ?: $this->attributes['name_ar']
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

    public function getTranslatedNameAttribute(): string
    {
        return $this->name;
    }

    public function getTranslatedDescriptionAttribute(): ?string
    {
        return $this->description;
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }
}
