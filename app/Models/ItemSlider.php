<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemSlider extends Model
{
    public const PRIORITY_NORMAL = 'normal';
    public const PRIORITY_HIGH = 'high';
    public const PRIORITY_TOP = 'top';

    protected $fillable = [
        'item_id',
        'is_active',
        'priority',
        'click_count',
        'admin_note',
    ];

    protected $appends = [
        'priority_label',
        'priority_badge_class',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'click_count' => 'integer',
        ];
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public static function priorities(): array
    {
        return [
            self::PRIORITY_NORMAL => 'Normal',
            self::PRIORITY_HIGH => 'High',
            self::PRIORITY_TOP => 'Top',
        ];
    }

    public function getPriorityLabelAttribute(): string
    {
        return self::priorities()[$this->priority] ?? 'Normal';
    }

    public function getPriorityBadgeClassAttribute(): string
    {
        return match ($this->priority) {
            self::PRIORITY_TOP => 'badge-danger',
            self::PRIORITY_HIGH => 'badge-warning',
            self::PRIORITY_NORMAL => 'badge-primary',
            default => 'badge-primary',
        };
    }
}
