<?php

namespace App\Modules\HelpDesk\Http\Requests;

use Illuminate\Support\Str;
use Illuminate\Foundation\Http\FormRequest;

class StoreTicketMessageRequest extends FormRequest
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
            //'ticket_id'   => ['required', 'string', 'size:36', 'exists:tickets,id'],
            'body'        => ['required', 'string'],
            'is_internal' => ['sometimes', 'boolean'],
            'author_id'   => ['prohibited'],
            'seq'         => ['prohibited'],
            'deleted_at'  => ['prohibited'],
            'created_at'  => ['prohibited'],
            'updated_at'  => ['prohibited'],
            'id'          => ['prohibited'],
        ];
    }

    protected function prepareForValidation(): void
    {
        foreach (['ticket_id', 'author_id'] as $key) {
            if ($this->filled($key)) {
                $this->merge([
                    $key => Str::lower((string) $this->input($key)),
                ]);
            }
        }

        if ($this->has('is_internal')) {
            $this->merge([
                'is_internal' => filter_var(
                    $this->input('is_internal'),
                    FILTER_VALIDATE_BOOLEAN,
                    FILTER_NULL_ON_FAILURE
                ),
            ]);
        }
    }

    public function attributes(): array
    {
        return [
            'ticket_id'   => 'ticket',
            'author_id'   => 'author',
            'body'        => 'message body',
            'is_internal' => 'internal note flag',
        ];
    }
}
