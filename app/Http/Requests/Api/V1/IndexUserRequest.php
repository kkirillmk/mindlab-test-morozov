<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class IndexUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'per_page.integer' => 'Количество элементов на странице должно быть числом',
            'per_page.min' => 'Минимальное количество элементов на странице: :min',
            'per_page.max' => 'Максимальное количество элементов на странице: :max',
        ];
    }

    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);

        if (!isset($validated['per_page'])) {
            $validated['per_page'] = 15;
        }

        return $validated;
    }
}
