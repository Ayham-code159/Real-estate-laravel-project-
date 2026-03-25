<?php

namespace App\Http\Requests\Admin\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreAdminRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:admins,email'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
            'is_super_admin' => ['nullable', 'boolean'],
            'can_manage_users' => ['nullable', 'boolean'],
            'can_manage_business_accounts' => ['nullable', 'boolean'],
        ];
    }
}
