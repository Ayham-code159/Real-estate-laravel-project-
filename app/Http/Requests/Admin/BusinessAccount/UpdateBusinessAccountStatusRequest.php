<?php

namespace App\Http\Requests\Admin\BusinessAccount;

use App\Models\BusinessAccount;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBusinessAccountStatusRequest extends FormRequest
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
            'status' => [
                'required',
                'integer',
                Rule::in([
                    BusinessAccount::STATUS_PENDING,
                    BusinessAccount::STATUS_APPROVED,
                    BusinessAccount::STATUS_REJECTED,
                ]),
            ],
            'rejection_reason' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ];
    }
}
