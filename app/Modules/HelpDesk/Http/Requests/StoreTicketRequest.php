<?php

namespace App\Modules\HelpDesk\Http\Requests;

use App\Modules\HelpDesk\Models\Ticket;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Ticket::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'subject'               => ['required', 'string', 'max:255'],
            'description'           => ['nullable', 'string'],
            'ticket_category_id'    => ['required', 'uuid', 'exists:ticket_categories,id'],
            'ticket_subcategory_id' => ['nullable', 'uuid', 'exists:ticket_subcategories,id'],
            'ticket_status_id'      => ['nullable', 'uuid', 'exists:ticket_statuses,id'], // se não vier, usar default no Model
            'opened_by_id'          => ['nullable', 'uuid', 'exists:users,id'],
            'requester_id'          => ['nullable', 'uuid', 'exists:users,id'],
            'assigned_to_id'        => ['nullable', 'uuid', 'exists:users,id'],
            'priority'              => ['nullable', Rule::in(['low', 'normal', 'high', 'urgent'])], // ajuste se usar enum/integer
            // Blindagens:
            'ticket_number'         => ['prohibited'], // gerado pelo sistema
            'comments_count'        => ['prohibited'],
            'attachments_count'     => ['prohibited'],
        ];
    }

    protected function prepareForValidation(): void
    {
        // Normalizações leves
        if ($this->has('subject')) {
            $this->merge(['subject' => trim((string)$this->input('subject'))]);
        }

        // Garanta UUIDs em lowercase
        foreach (['ticket_category_id', 'ticket_subcategory_id', 'ticket_status_id', 'opened_by_id', 'requester_id', 'assigned_to_id'] as $key) {
            if ($this->filled($key)) {
                $this->merge([$key => Str::lower((string)$this->input($key))]);
            }
        }
    }

    public function attributes(): array
    {
        return [
            'ticket_category_id'    => 'categoria',
            'ticket_subcategory_id' => 'subcategoria',
            'ticket_status_id'      => 'status',
            'assigned_to_id'        => 'responsável',
        ];
    }
}
