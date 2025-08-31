<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserPhoneRequest extends FormRequest
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
            'country_code' => 'required|string|max:3',
            'area_code'    => 'required|string|max:5',
            'number'       => 'required|string|max:20',
            'label'        => 'sometimes|nullable|string|max:50',
            'is_primary'   => 'sometimes|boolean',
        ];
    }
}
