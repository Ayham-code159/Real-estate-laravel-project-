<?php

namespace App\Http\Requests\Category;

use App\Models\SubcategoryField;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSubcategoryFieldRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'label_en' => ['required', 'string', 'max:255'],
            'label_ar' => ['nullable', 'string', 'max:255'],

            'field_type' => ['required', Rule::in(SubcategoryField::fieldTypes())],
            'is_required' => ['nullable', 'boolean'],

            'options' => ['nullable', 'array'],
            'options.*' => ['string', 'max:255'],

            'min_value' => ['nullable', 'numeric'],
            'max_value' => ['nullable', 'numeric'],

            'min_date' => ['nullable', 'date'],
            'max_date' => ['nullable', 'date', 'after_or_equal:min_date'],

            'text_rule' => ['nullable', Rule::in(SubcategoryField::textRules())],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
