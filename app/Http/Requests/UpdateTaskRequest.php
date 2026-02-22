<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'status' => ['sometimes', Rule::in(['pending', 'inProgress', 'done'])],
            'items' => ['sometimes', 'array'],
            'items.*.title' => ['required_with:items', 'string', 'max:255'],
            'items.*.is_completed' => ['sometimes', 'boolean'],
            'items.*.priority' => ['sometimes', Rule::in(['low', 'medium', 'high'])],
        ];
    }
}
