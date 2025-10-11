<?php

namespace App\Http\Requests\Appointments;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Policy em viewAny/view filtra o que o usuário pode ver
    }

    public function rules(): array
    {
        return [
            'status'          => ['sometimes', Rule::in(['pending','scheduled','completed','canceled','no_show'])],
            'psychologist_id' => ['sometimes','uuid'],
            'user_id'         => ['sometimes','uuid'],
            'per_page'        => ['sometimes','integer','min:1','max:200'],
            'order_by'        => ['sometimes', Rule::in(['created_at','status'])],
            'dir'             => ['sometimes', Rule::in(['asc','desc'])],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'order_by' => $this->input('order_by', 'created_at'),
            'dir'      => $this->input('dir', 'desc'),
        ]);
    }
}
