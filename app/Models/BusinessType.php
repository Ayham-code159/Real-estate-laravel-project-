<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BusinessType extends Model
{
    protected $fillable = [
        'name',
    ];

    public function businessAccounts(): HasMany
    {
        return $this->hasMany(BusinessAccount::class);
    }
}
