<?php

namespace App\Modules\HelpDesk\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketStatusRequest extends FormRequest
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
            'name'        => ['required', 'string', 'max:255'],
            'is_final'    => ['required', 'boolean'],
            'position'    => ['required', 'integer'],
            'id'          => ['prohibited'],
            'created_at'  => ['prohibited'],
            'updated_at'  => ['prohibited'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('name')) {
            $this->merge([
                'name' => trim((string) $this->input('name')),
            ]);
        }

        if ($this->has('description')) {
            $this->merge([
                'description' => trim((string) $this->input('description')),
            ]);
        }
    }

    public function attributes(): array
    {
        return [
            'name'        => 'status name',
            'description' => 'status description',
            'is_closed'   => 'closed flag',
        ];
    }
}
