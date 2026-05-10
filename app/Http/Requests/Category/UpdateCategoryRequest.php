<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $categoryId = $this->route('category')?->id;

        return [
            'name_en' => ['required', 'string', 'max:255', Rule::unique('categories', 'name_en')->ignore($categoryId)],
            'name_ar' => ['nullable', 'string', 'max:255', Rule::unique('categories', 'name_ar')->ignore($categoryId)],
            'description_en' => ['nullable', 'string'],
            'description_ar' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
