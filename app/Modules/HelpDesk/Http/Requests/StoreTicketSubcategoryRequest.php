<?php

namespace App\Modules\HelpDesk\Http\Requests;

use Illuminate\Support\Str;
use Illuminate\Foundation\Http\FormRequest;

class StoreTicketSubcategoryRequest extends FormRequest
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
            'ticket_category_id' => ['required', 'string', 'size:36', 'exists:ticket_categories,id'],
            'name'               => ['required', 'string', 'max:255'],
            'position'           => ['nullable', 'integer', 'min:0'],

            'slug'        => ['prohibited'],
            'id'          => ['prohibited'],
            'created_at'  => ['prohibited'],
            'updated_at'  => ['prohibited'],
            'deleted_at'  => ['prohibited'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('name')) {
            $this->merge([
                'name' => trim((string) $this->input('name')),
            ]);
        }

        if ($this->filled('ticket_category_id')) {
            $this->merge([
                'ticket_category_id' => Str::lower((string) $this->input('ticket_category_id')),
            ]);
        }
    }

    public function attributes(): array
    {
        return [
            'ticket_category_id' => 'category',
            'name'               => 'subcategory name',
            'position'           => 'display order',
        ];
    }
}
