<?php

namespace App\Http\Requests\Api\V1;

use App\Services\Auth\DTO\Credentials;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:6', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email обязателен для заполнения',
            'email.email' => 'Некорректный формат email',
            'email.max' => 'Email не может быть длиннее :max символов',
            'password.required' => 'Пароль обязателен для заполнения',
            'password.min' => 'Пароль должен содержать минимум :min символов',
            'password.max' => 'Пароль не может быть длиннее :max символов',
        ];
    }

    public function toDTO(): Credentials
    {
        $validated = $this->validated();
        
        return new Credentials(
            email: $validated['email'],
            password: $validated['password']
        );
    }
}
