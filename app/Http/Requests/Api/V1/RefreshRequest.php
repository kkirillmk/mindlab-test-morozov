<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class RefreshRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'refresh_token' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'refresh_token.required' => 'Refresh token обязателен',
            'refresh_token.string' => 'Refresh token должен быть строкой',
            'refresh_token.max' => 'Refresh token не может быть длиннее :max символов',
        ];
    }
}
