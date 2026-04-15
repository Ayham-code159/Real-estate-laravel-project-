<?php

namespace App\Http\Requests\ListingRequest;

use Illuminate\Foundation\Http\FormRequest;

class StoreListingRequestRatingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string'],
        ];
    }
}
