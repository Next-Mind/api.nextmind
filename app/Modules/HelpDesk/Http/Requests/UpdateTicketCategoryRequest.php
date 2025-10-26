<?php

namespace App\Modules\HelpDesk\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTicketCategoryRequest extends FormRequest
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
        $id = $this->route('ticketCategory')?->getKey();
        return [
            'name'       => ['sometimes', 'required', 'string', 'max:100'],
            'slug'       => ['nullable', 'string', 'max:120', Rule::unique('ticket_categories', 'slug')->ignore($id)],
            'position'   => ['nullable', 'integer', 'min:0'],
        ];
    }
}
