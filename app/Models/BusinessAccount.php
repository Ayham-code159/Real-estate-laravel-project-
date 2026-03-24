<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusinessAccount extends Model
{
    protected $fillable = [
        'user_id',
        'business_type_id',
        'city_id',
        'business_name',
        'status',
        'rejection_reason',
    ];

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
}
