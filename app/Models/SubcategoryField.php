<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubcategoryField extends Model
{
    public const TYPE_TEXT = 'text';
    public const TYPE_NUMBER = 'number';
    public const TYPE_SELECT = 'select';
    public const TYPE_BOOLEAN = 'boolean';
    public const TYPE_DATE = 'date';

    public const TEXT_RULE_NONE = 'none';
    public const TEXT_RULE_LETTERS_ONLY = 'letters_only';
    public const TEXT_RULE_LETTERS_SPACES_ONLY = 'letters_spaces_only';
    public const TEXT_RULE_ALPHA_NUMERIC = 'alpha_numeric';

    protected $fillable = [
        'subcategory_id',
        'field_key',
        'label_en',
        'label_ar',
        'field_type',
        'is_required',
        'options',
        'min_value',
        'max_value',
        'min_date',
        'max_date',
        'text_rule',
        'sort_order',
    ];

    protected $appends = [
        'translated_label',
    ];

    protected function casts(): array
    {
        return [
            'is_required' => 'boolean',
            'options' => 'array',
            'min_value' => 'decimal:2',
            'max_value' => 'decimal:2',
            'min_date' => 'date',
            'max_date' => 'date',
            'sort_order' => 'integer',
        ];
    }

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function getTranslatedLabelAttribute(): string
    {
        if (app()->getLocale() === 'ar') {
            return $this->label_ar ?: $this->label_en;
        }

        return $this->label_en ?: $this->label_ar;
    }

    public static function fieldTypes(): array
    {
        return [
            self::TYPE_TEXT,
            self::TYPE_NUMBER,
            self::TYPE_SELECT,
            self::TYPE_BOOLEAN,
            self::TYPE_DATE,
        ];
    }

    public static function textRules(): array
    {
        return [
            self::TEXT_RULE_NONE,
            self::TEXT_RULE_LETTERS_ONLY,
            self::TEXT_RULE_LETTERS_SPACES_ONLY,
            self::TEXT_RULE_ALPHA_NUMERIC,
        ];
    }
}
