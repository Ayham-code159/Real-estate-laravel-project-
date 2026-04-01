<?php

namespace App\Http\Requests\ServiceListing;

use Illuminate\Foundation\Http\FormRequest;

class UpdateServiceListingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'service_id' => ['required', 'integer', 'exists:services,id'],
            'service_subcategory_id' => ['required', 'integer', 'exists:service_subcategories,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'mode' => ['required', 'string', 'in:sell,rent'],
            'price_usd' => ['required', 'numeric', 'min:0'],
            'metadata' => ['nullable', 'array'],
        ];
    }
}
