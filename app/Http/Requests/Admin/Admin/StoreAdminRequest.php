<?php

namespace App\Http\Requests\Admin\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAdminRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $allowedPermissions = [
            'can_manage_users',
            'can_manage_business_accounts',
            'can_manage_business_types',
            'can_manage_categories',
            'can_manage_items',
            'can_manage_sliders',
        ];

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:admins,email'],
            'password' => ['required', 'string', 'min:8'],

            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', Rule::in($allowedPermissions)],
        ];
    }
}
