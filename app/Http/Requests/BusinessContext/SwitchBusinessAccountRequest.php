<?php

namespace App\Http\Requests\BusinessContext;

use Illuminate\Foundation\Http\FormRequest;

class SwitchBusinessAccountRequest extends FormRequest
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
            'business_account_id' => ['required', 'integer', 'exists:business_accounts,id'],
        ];
    }
}
