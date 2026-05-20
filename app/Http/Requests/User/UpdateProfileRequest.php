<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255', 'regex:/^[\pL\s]+$/u'],
            'last_name' => ['required', 'string', 'max:255', 'regex:/^[\pL\s]+$/u'],
        ];
    }
}
