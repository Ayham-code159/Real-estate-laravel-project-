<?php

namespace App\Http\Requests\Admin\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class UpdateAdminRequest extends FormRequest
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
        $adminId = $this->route('admin')->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('admins', 'email')->ignore($adminId)],
            'password' => ['nullable', 'confirmed', Password::min(8)->letters()->numbers()],
            'is_super_admin' => ['nullable', 'boolean'],
            'can_manage_users' => ['nullable', 'boolean'],
            'can_manage_business_accounts' => ['nullable', 'boolean'],
        ];
    }
}
