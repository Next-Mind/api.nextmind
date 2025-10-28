<?php

namespace App\Modules\HelpDesk\Http\Requests;

use App\Modules\HelpDesk\Models\Ticket;
use Illuminate\Support\Str;
use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'subject'               => ['required', 'string', 'max:255'],

            'opened_by_id'          => ['nullable', 'string', 'size:36', 'exists:users,id'],
            'requester_id'          => ['nullable', 'string', 'size:36', 'exists:users,id'],
            'assigned_to_id'        => ['nullable', 'string', 'size:36', 'exists:users,id'],

            'ticket_category_id'    => ['required', 'string', 'size:36', 'exists:ticket_categories,id'],
            'ticket_subcategory_id' => ['required', 'string', 'size:36', 'exists:ticket_subcategories,id'],
            'ticket_status_id'      => ['nullable', 'string', 'size:36', 'exists:ticket_statuses,id'],

            'first_response_due_at' => ['nullable', 'date'],
            'resolution_due_at'     => ['nullable', 'date'],

            'ticket_number'     => ['prohibited'],

            'resolved_at'       => ['prohibited'],
            'closed_at'         => ['prohibited'],
            'deleted_at'        => ['prohibited'],
            'created_at'        => ['prohibited'],
            'updated_at'        => ['prohibited'],

            'comments_count'    => ['prohibited'],
            'attachments_count' => ['prohibited'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('subject')) {
            $this->merge([
                'subject' => trim((string) $this->input('subject')),
            ]);
        }
        foreach (
            [
                'opened_by_id',
                'requester_id',
                'assigned_to_id',
                'ticket_category_id',
                'ticket_subcategory_id',
                'ticket_status_id',
            ] as $key
        ) {
            if ($this->filled($key)) {
                $this->merge([
                    $key => Str::lower((string) $this->input($key)),
                ]);
            }
        }
    }

    public function attributes(): array
    {
        return [
            'subject'                => 'subject',
            'opened_by_id'           => 'opened by user',
            'requester_id'           => 'requester',
            'assigned_to_id'         => 'assignee',
            'ticket_category_id'     => 'category',
            'ticket_subcategory_id'  => 'subcategory',
            'ticket_status_id'       => 'status',
            'first_response_due_at'  => 'first response due date',
            'resolution_due_at'      => 'resolution due date',
        ];
    }
}
