<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RentServiceSubtype extends Model
{
    protected $fillable = [
        'name',
    ];

    public function rentServices(): HasMany
    {
        return $this->hasMany(RentService::class);
    }
}
