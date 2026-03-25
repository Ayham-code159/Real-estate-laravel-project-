<?php

namespace App\Http\Requests\Offering;

use App\Models\Offering;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreOfferingRequest extends FormRequest
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
            'type' => ['required', 'string', Rule::in(Offering::types())],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'metadata' => ['nullable', 'array'],
        ];
    }
}
