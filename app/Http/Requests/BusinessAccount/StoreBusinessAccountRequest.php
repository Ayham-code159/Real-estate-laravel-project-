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
            'business_name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\pL\s]+$/u',
            ],
            'business_type_id' => [
                'required',
                'integer',
                'exists:business_types,id',
            ],
            'city_id' => [
                'required',
                'integer',
                'exists:cities,id',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'business_name.regex' => 'Business name must contain letters only.',
        ];
    }
}
