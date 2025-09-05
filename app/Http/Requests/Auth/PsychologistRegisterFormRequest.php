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
            'cpf' => 'unique:users|cpf',
            
            //INFORMAÇÕES BÁSICAS DO PSICÓLOGO
            'crp' => ['required','string','max:50','unique:psychologist_profiles'],
            'speciality' => ['required','string','max:100'],
            'bio' => ['nullable','string','max:4000'],
            
            // ENDEREÇO (um endereço)
            'address' => ['required','array'],
            'address.label' => ['required','string','max:255'],
            'address.postal_code' => ['required','string','regex:/^\d{5}-?\d{3}$/'], // CEP
            'address.street' => ['required','string','max:255'],
            'address.number' => ['required','string','max:255'],
            'address.complement' => ['nullable','string','max:255'],
            'address.neighborhood' => ['required','string','max:255'],
            'address.city' => ['required','string','max:255'],
            'address.state' => ['required','string','size:2'], // ex: SP
            'address.country' => ['nullable','string','max:20'],
            'address.is_primary' => ['sometimes','boolean'],
            
            // TELEFONE (um telefone)
            'phone' => ['required','array'],
            'phone.label' => ['required','string','max:40'],
            'phone.country_code' => ['required','string','regex:/^\d{1,4}$/'], // ex: 55
            'phone.area_code' => ['required','string','regex:/^\d{1,3}$/'],    // ex: 19
            'phone.number' => [
                'required','string','regex:/^\d{8,12}$/',
                Rule::unique('user_phones','number')->where(fn ($q) => $q
                ->where('country_code', $this->input('phone.country_code'))
                ->where('area_code', $this->input('phone.area_code'))
            ),
        ],
        'phone.is_whatsapp' => ['required','boolean'],
        'phone.is_primary' => ['sometimes','boolean'],
    ];
}
}
