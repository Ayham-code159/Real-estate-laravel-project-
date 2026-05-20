<?php

namespace App\Http\Requests\Chat;

use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'body' => ['nullable', 'string', 'max:5000', 'required_without_all:audio,image'],
            'audio' => ['nullable', 'file', 'mimes:webm,mp3,wav,ogg,m4a', 'max:10240'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'body.required_without_all' => 'Message text, voice message, or image is required.',

            'audio.file' => 'The voice message must be a valid audio file.',
            'audio.mimes' => 'The voice message must be webm, mp3, wav, ogg, or m4a.',
            'audio.max' => 'The voice message must not be larger than 10MB.',

            'image.image' => 'The uploaded file must be an image.',
            'image.mimes' => 'The image must be jpg, jpeg, png, or webp.',
            'image.max' => 'The image must not be larger than 5MB.',
        ];
    }
}
