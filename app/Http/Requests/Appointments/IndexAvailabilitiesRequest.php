<?php

namespace App\Http\Requests\Appointments;

use Illuminate\Foundation\Http\FormRequest;

class IndexAvailabilitiesRequest extends FormRequest
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
            'start_date' => 'required|date|after_or_equal:now',
            'end_date' => 'required|date|after_or_equal:start_date'
        ];
    }
    protected function prepareForValidation()
    {
        $this->merge([
            'start_date' => $this->query('start_date') ?? now()->toDateTimeString(),
            'end_date'   => $this->query('end_date')   ?? now()->addDay()->toDateTimeString(),
        ]);
    }
}
