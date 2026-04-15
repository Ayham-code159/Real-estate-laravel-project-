<?php

namespace App\Http\Requests\ListingRequest;

use Illuminate\Foundation\Http\FormRequest;

class StoreListingRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'service_listing_id' => ['required', 'integer', 'exists:service_listings,id'],
            'requested_for' => ['required', 'date', 'after:now'],
            'description' => ['required', 'string'],
            'request_metadata' => ['nullable', 'array'],
        ];
    }
}
