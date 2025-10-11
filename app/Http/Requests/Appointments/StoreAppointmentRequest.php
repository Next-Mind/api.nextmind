<?php

namespace App\Http\Requests\Appointments;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Policy 'create' já cobre a autorização no controller.
        return true;
    }

    public function rules(): array
    {
        return [
            'availability_id' => [
                'required',
                'uuid',
                Rule::exists('availabilities', 'id'),
                Rule::unique('appointments', 'availability_id'),
            ],
            'psychologist_id' => [
                'required',
                'uuid',
                Rule::exists('users', 'id'),
            ],
            'user_id' => [
                'nullable',
                'uuid',
                Rule::exists('users', 'id'),
            ],
            'description' => ['nullable', 'string', 'max:5000'],
            'status' => ['nullable', Rule::in(['pending','scheduled','completed','canceled','no_show'])],
        ];
    }

    public function prepareForValidation(): void
    {
        // default status
        if (!$this->filled('status')) {
            $this->merge(['status' => 'pending']);
        }
    }

    public function messages(): array
    {
        return [
            'availability_id.unique' => 'Esta disponibilidade já foi reservada.',
        ];
    }

    public function attributes(): array
    {
        return [
            'availability_id' => 'disponibilidade',
            'psychologist_id' => 'psicólogo',
            'user_id' => 'paciente',
        ];
    }
}
