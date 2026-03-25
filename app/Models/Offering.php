<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Offering extends Model
{
    public const TYPE_PRODUCT = 'product';
    public const TYPE_SERVICE = 'service';
    public const TYPE_RENTAL = 'rental';

    protected $fillable = [
        'business_account_id',
        'type',
        'title',
        'description',
        'price',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'metadata' => 'array',
        ];
    }

    public function businessAccount(): BelongsTo
    {
        return $this->belongsTo(BusinessAccount::class);
    }

    public static function types(): array
    {
        return [
            self::TYPE_PRODUCT,
            self::TYPE_SERVICE,
            self::TYPE_RENTAL,
        ];
    }
}
