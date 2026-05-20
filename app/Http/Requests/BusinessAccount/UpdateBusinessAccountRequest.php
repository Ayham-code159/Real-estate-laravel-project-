<?php

namespace App\Http\Requests\BusinessAccount;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBusinessAccountRequest extends FormRequest
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

            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'location_label' => ['nullable', 'string', 'max:255'],
        ];
    }
}
