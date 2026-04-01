<?php

namespace App\Http\Requests\Admin\ServiceListing;

use Illuminate\Foundation\Http\FormRequest;

class UpdateServiceListingStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'integer', 'in:1,2,3'],
            'rejection_reason' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
