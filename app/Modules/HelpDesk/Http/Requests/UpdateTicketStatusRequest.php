<?php

namespace App\Modules\HelpDesk\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTicketStatusRequest extends FormRequest
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
            'name'     => ['sometimes', 'string', 'max:255'],

            'is_final' => ['sometimes', 'boolean'],

            'position' => ['sometimes', 'integer', 'min:0'],

            'slug'       => ['prohibited'],
            'id'         => ['prohibited'],
            'created_at' => ['prohibited'],
            'updated_at' => ['prohibited'],
            'deleted_at' => ['prohibited'],
        ];
    }
}
