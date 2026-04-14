<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    protected $fillable = [
        'name',
        'name_en',
        'name_ar',
    ];

    protected $appends = [
        'translated_name',
    ];

    public function businessAccounts(): HasMany
    {
        return $this->hasMany(BusinessAccount::class);
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
