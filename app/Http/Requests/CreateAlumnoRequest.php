<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateAlumnoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'             => ['required','string','max:255'],
            'apellido'         => ['required','string','max:255'],
            'email'            => ['required','email','unique:users,email'],
            'matricula'        => ['required','string','unique:alumnos,matricula'],
            'grupo'            => ['required','exists:grupos,id'],
            'curp'             => ['required','string','max:18'],
            'fecha_nacimiento' => ['required','date'],
            'sexo'             => ['required','in:M,F,Otro'],
            'telefono'         => ['nullable','string']
        ];
    }
}
