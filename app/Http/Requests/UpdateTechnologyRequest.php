<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTechnologyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('technologies', 'name')->ignore($this->route('technology')),
            ],
            'category' => 'required|string|max:255|in:languages,frontend,backend,mobile,database,infrastructure,automation,tools,ui',
            'is_active' => 'required|boolean',
            'invert_dark' => 'required|boolean',
            'logo' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp,svg|max:2048',
        ];
    }
}
