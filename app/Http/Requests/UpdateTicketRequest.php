<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\Models\Tickets\Ticket;

class UpdateTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var Ticket $ticket */
        $ticket = $this->route('ticket');
        return $this->user()?->can('update', $ticket) ?? false;
    }

    public function rules(): array
    {
        return [
            'subject'               => ['sometimes','string','max:255'],
            'description'           => ['sometimes','nullable','string'],
            'ticket_category_id'    => ['sometimes','uuid','exists:ticket_categories,id'],
            'ticket_subcategory_id' => ['sometimes','nullable','uuid','exists:ticket_subcategories,id'],
            'ticket_status_id'      => ['sometimes','uuid','exists:ticket_statuses,id'],
            'assigned_to_id'        => ['sometimes','nullable','uuid','exists:users,id'],
            'requester_id'          => ['sometimes','uuid','exists:users,id'],
            'priority'              => ['sometimes', Rule::in(['low','normal','high','urgent'])],

            // Campos imutáveis/administrados pelo sistema
            'opened_by_id'          => ['prohibited'],
            'ticket_number'         => ['prohibited'],
            'comments_count'        => ['prohibited'],
            'attachments_count'     => ['prohibited'],
            'created_at'            => ['prohibited'],
            'updated_at'            => ['prohibited'],
            'deleted_at'            => ['prohibited'],
        ];
    }

    protected function prepareForValidation(): void
    {
        foreach ([
            'ticket_category_id','ticket_subcategory_id','ticket_status_id',
            'assigned_to_id','requester_id'
        ] as $key) {
            if ($this->filled($key)) {
                $this->merge([$key => Str::lower((string)$this->input($key))]);
            }
        }
        if ($this->has('subject')) {
            $this->merge(['subject' => trim((string)$this->input('subject'))]);
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
