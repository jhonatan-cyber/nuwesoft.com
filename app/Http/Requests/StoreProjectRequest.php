<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'category' => 'required|string|in:' . implode(',', array_column(\App\Enums\ProjectCategory::cases(), 'value')),
            'technologies' => 'nullable|array',
            'desc' => 'nullable|string|max:5000',
            'icon' => 'nullable|string|max:100',
            'images.*' => 'nullable|image|mimes:jpeg,png,gif,webp|max:5120',
            'project_url' => 'nullable|url|max:500',
            'is_active' => 'nullable|boolean',
        ];
    }
}
