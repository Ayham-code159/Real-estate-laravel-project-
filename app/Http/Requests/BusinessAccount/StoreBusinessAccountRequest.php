<?php

namespace App\Http\Requests\BusinessAccount;

use Illuminate\Foundation\Http\FormRequest;

class StoreBusinessAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'business_type_id' => ['required', 'integer', 'exists:business_types,id'],
            'city_id' => ['required', 'integer', 'exists:cities,id'],

            'business_name_en' => ['required', 'string', 'max:255', 'regex:/^[\pL\s]+$/u'],
            'business_name_ar' => ['nullable', 'string', 'max:255', 'regex:/^[\pL\s]+$/u'],
        ];
    }

    public function messages(): array
    {
        return [
            'business_type_id.required' => 'Business account type is required.',
            'business_type_id.exists' => 'Selected business account type is invalid.',
            'business_name_en.required' => 'Business name in English is required.',
            'business_name_en.regex' => 'Business name in English must contain letters only.',
            'business_name_ar.regex' => 'Business name in Arabic must contain letters only.',
        ];
    }
}
