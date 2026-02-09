<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'new_password' => ['required', 'string', 'min:6', 'max:255', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'new_password.required' => 'Пароль обязателен',
            'new_password.min' => 'Пароль должен содержать минимум :min символов',
            'new_password.max' => 'Пароль не может быть длиннее :max символов',
            'new_password.confirmed' => 'Подтверждение пароля не совпадает',
        ];
    }
}
