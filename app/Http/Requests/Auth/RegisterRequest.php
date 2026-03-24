<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\pL\s]+$/u'
            ],
            'last_name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\pL\s]+$/u'
            ],
            'identifier' => [
                'required',
                'string',
                'max:255'
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[A-Za-z])(?=.*[\d\W]).+$/'
            ],
            'terms_accepted' => [
                'required',
                'accepted'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.regex' => 'First name must contain letters only.',
            'last_name.regex' => 'Last name must contain letters only.',
            'password.regex' => 'Password must contain at least one letter and one number or special character.',
        ];
    }
}
