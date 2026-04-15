<?php

namespace App\Http\Requests\ListingRequest;

use Illuminate\Foundation\Http\FormRequest;

class UpdateListingRequestStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'integer', 'in:1,2,3,4'],
            'seller_response_note' => ['nullable', 'string'],
        ];
    }
}
