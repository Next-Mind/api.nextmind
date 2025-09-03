<?php

namespace App\Http\Requests\Auth;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class PsychologistRegisterFormRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3',
            'email' => 'required|email|unique:users',
            'password' => [
                'required',
                Password::min(8)
                    ->letters()
                    ->numbers()
            ],
            'birth_date' => [
                'required',
                Rule::date()->format('Y-m-d')
            ],
            'cpf' => 'cpf|unique:users',

            //INFORMAÇÕES BÁSICAS DO PSICÓLOGO
            'crp' => ['required','string','max:50'],
            'speciality' => ['required','string','max:100'],
            'bio' => ['nullable','string','max:4000']
        ];
    }
}
