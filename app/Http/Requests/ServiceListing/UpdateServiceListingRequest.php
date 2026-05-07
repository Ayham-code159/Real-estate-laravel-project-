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

            'title_en' => ['required', 'string', 'max:255'],
            'title_ar' => ['nullable', 'string', 'max:255'],

            'description_en' => ['nullable', 'string'],
            'description_ar' => ['nullable', 'string'],

            'mode' => ['required', 'string', 'in:sell,rent'],
            'price_usd' => ['required', 'numeric', 'min:0'],
            'price_syp' => ['required', 'numeric', 'min:0'],

            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'location_label' => ['nullable', 'string', 'max:255'],

            'metadata' => ['nullable', 'array'],

            'main_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'sub_photos' => ['nullable', 'array'],
            'sub_photos.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }
}
