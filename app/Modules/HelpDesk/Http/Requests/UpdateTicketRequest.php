<?php

namespace App\Modules\HelpDesk\Http\Requests;

use App\Modules\HelpDesk\Models\Ticket;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class UpdateTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'subject'               => ['sometimes', 'string', 'max:255'],

            'opened_by_id'          => ['sometimes', 'nullable', 'string', 'size:36', 'exists:users,id'],
            'requester_id'          => ['sometimes', 'nullable', 'string', 'size:36', 'exists:users,id'],
            'assigned_to_id'        => ['sometimes', 'nullable', 'string', 'size:36', 'exists:users,id'],

            'ticket_category_id'    => ['sometimes', 'string', 'size:36', 'exists:ticket_categories,id'],
            'ticket_subcategory_id' => ['sometimes', 'nullable', 'string', 'size:36', 'exists:ticket_subcategories,id'],
            'ticket_status_id'      => ['sometimes', 'string', 'size:36', 'exists:ticket_statuses,id'],

            'first_response_due_at' => ['sometimes', 'nullable', 'date'],
            'resolution_due_at'     => ['sometimes', 'nullable', 'date'],

            'resolved_at'           => ['sometimes', 'nullable', 'date'],
            'closed_at'             => ['sometimes', 'nullable', 'date'],

            'ticket_number'     => ['prohibited'],
            'comments_count'    => ['prohibited'],
            'attachments_count' => ['prohibited'],

            'created_at'        => ['prohibited'],
            'updated_at'        => ['prohibited'],
            'deleted_at'        => ['prohibited'],
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
            'resolved_at'            => 'resolved at',
            'closed_at'              => 'closed at',
        ];
    }
}
