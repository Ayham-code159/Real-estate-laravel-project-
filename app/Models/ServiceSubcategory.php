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
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function serviceListings(): HasMany
    {
        return $this->hasMany(ServiceListing::class, 'service_subcategory_id');
    }
}
