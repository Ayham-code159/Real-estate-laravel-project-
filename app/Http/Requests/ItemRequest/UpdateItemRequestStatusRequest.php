<?php

namespace App\Http\Requests\ItemRequest;

use App\Models\ItemRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateItemRequestStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => [
                'required',
                'string',
                Rule::in([
                    ItemRequest::STATUS_IN_PROGRESS,
                    ItemRequest::STATUS_COMPLETED,
                    ItemRequest::STATUS_REJECTED,
                ]),
            ],
        ];
    }
}
