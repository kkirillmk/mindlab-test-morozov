<?php

namespace App\Http\Requests\Api\V1;

use App\Services\User\DTO\UpdateUser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user')->id;

        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'email', 'max:255', Rule::unique('users')->ignore($userId)],
            'is_active' => ['sometimes', 'boolean'],
            'role_ids' => ['sometimes', 'array'],
            'role_ids.*' => ['integer', 'exists:roles,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.max' => 'Имя не может быть длиннее :max символов',
            'email.email' => 'Некорректный формат email',
            'email.max' => 'Email не может быть длиннее :max символов',
            'email.unique' => 'Пользователь с таким email уже существует',
            'is_active.boolean' => 'Поле is_active должно быть булевым значением',
            'role_ids.array' => 'Роли должны быть массивом',
            'role_ids.*.integer' => 'ID роли должен быть числом',
            'role_ids.*.exists' => 'Выбранная роль не существует',
        ];
    }

    public function toDTO(): UpdateUser
    {
        $validated = $this->validated();
        
        return new UpdateUser(
            name: $validated['name'] ?? null,
            email: $validated['email'] ?? null,
            password: $validated['password'] ?? null,
            isActive: $validated['is_active'] ?? null,
            roleIds: $validated['role_ids'] ?? null
        );
    }
}
