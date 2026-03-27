<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SellServiceSubtype extends Model
{
    protected $fillable = [
        'name',
    ];

    public function sellServices(): HasMany
    {
        return $this->hasMany(SellService::class);
    }
}
