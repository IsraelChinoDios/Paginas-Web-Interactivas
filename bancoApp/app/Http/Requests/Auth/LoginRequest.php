<?php

namespace App\Http\Requests\Auth;

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
            'correo'   => ['required','string','max:255'],
            'password' => ['required','string'],
            'remember' => ['nullable','boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'correo.required'   => 'El correo es obligatorio.',
            'password.required' => 'La contraseña es obligatoria.',
        ];
    }
}
