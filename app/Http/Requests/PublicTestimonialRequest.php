<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PublicTestimonialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_name' => 'required|string|max:255',
            'client_role' => 'nullable|string|max:255',
            'client_company' => 'nullable|string|max:255',
            'content' => 'required|string|min:10|max:2000',
            'rating' => 'required|integer|min:1|max:5',
            'form_token' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'client_name.required' => 'Por favor ingresa tu nombre.',
            'content.required' => 'Por favor escribe tu reseña.',
            'content.min' => 'La reseña debe tener al menos 10 caracteres.',
            'content.max' => 'La reseña no puede superar los 2000 caracteres.',
            'rating.required' => 'Por favor selecciona una calificación.',
            'rating.min' => 'La calificación mínima es 1 estrella.',
            'rating.max' => 'La calificación máxima es 5 estrellas.',
        ];
    }
}
