<?php

namespace App\Http\Requests\Api\V1;

use App\Services\Role\DTO\CreateRole;
use Illuminate\Foundation\Http\FormRequest;

class StoreRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'slug' => ['sometimes', 'string', 'max:255', 'unique:roles,slug'],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Название роли обязательно для заполнения',
            'name.unique' => 'Роль с таким названием уже существует',
            'name.max' => 'Название роли не может быть длиннее :max символов',
            'slug.unique' => 'Slug роли уже существует',
            'slug.max' => 'Slug роли не может быть длиннее :max символов',
            'description.max' => 'Описание роли не может быть длиннее :max символов',
        ];
    }

    public function toDTO(): CreateRole
    {
        $validated = $this->validated();
        
        return new CreateRole(
            name: $validated['name'],
            slug: $validated['slug'] ?? null,
            description: $validated['description'] ?? null
        );
    }
}
