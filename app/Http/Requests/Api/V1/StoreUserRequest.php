<?php

namespace App\Http\Requests\Api\V1;

use App\Services\User\DTO\CreateUser;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
            'role_ids' => ['sometimes', 'array'],
            'role_ids.*' => ['integer', 'exists:roles,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Имя обязательно для заполнения',
            'name.max' => 'Имя не может быть длиннее :max символов',
            'email.required' => 'Email обязателен для заполнения',
            'email.email' => 'Некорректный формат email',
            'email.max' => 'Email не может быть длиннее :max символов',
            'email.unique' => 'Пользователь с таким email уже существует',
            'password.required' => 'Пароль обязателен для заполнения',
            'password.min' => 'Пароль должен содержать минимум :min символов',
            'password.max' => 'Пароль не может быть длиннее :max символов',
            'is_active.boolean' => 'Поле is_active должно быть булевым значением',
            'role_ids.array' => 'Роли должны быть массивом',
            'role_ids.*.integer' => 'ID роли должен быть числом',
            'role_ids.*.exists' => 'Выбранная роль не существует',
        ];
    }

    public function toDTO(): CreateUser
    {
        $validated = $this->validated();
        
        return new CreateUser(
            name: $validated['name'],
            email: $validated['email'],
            password: $validated['password'],
            isActive: $validated['is_active'] ?? true,
            roleIds: $validated['role_ids'] ?? []
        );
    }
}
