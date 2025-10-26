<?php

namespace App\Modules\Appointments\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Appointments\Models\Appointment;
use App\Modules\Appointments\Rules\ValidAppointmentStatusTransition;

class UpdateAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Policy 'update' no controller decide; aqui retorna true.
        return true;
    }

    public function rules(): array
    {
        /** @var Appointment $appointment */
        $appointment = $this->route('appointment');

        return [
            'description' => ['sometimes', 'nullable', 'string', 'max:5000'],
            'status'      => [
                'sometimes',
                Rule::in(['pending', 'scheduled', 'completed', 'canceled', 'no_show']),
                new ValidAppointmentStatusTransition(optional($appointment)->status),
            ],

            // Em geral não permitimos trocar availability/psychologist via update.
            // Descomente se realmente precisar:
            // 'availability_id' => ['prohibited'],
            // 'psychologist_id' => ['prohibited'],
            // 'user_id'         => ['prohibited'],
        ];
    }
}
