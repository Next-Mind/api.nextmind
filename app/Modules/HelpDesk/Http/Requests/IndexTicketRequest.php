<?php

namespace App\Modules\HelpDesk\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IndexTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'ticket_number'         => ['sometimes', 'numeric'],
            'subject'               => ['sometimes', 'string', 'max:255'],

            'opened_by_id'          => ['sometimes', 'string'],
            'requester_id'          => ['sometimes', 'string'],
            'assigned_to_id'        => ['sometimes', 'string'],

            'ticket_category_id'    => ['sometimes', 'string'],
            'ticket_subcategory_id' => ['sometimes', 'string'],
            'ticket_status_id'      => ['sometimes', 'string'],
            'user_id'               => ['sometimes', 'string'],

            'created_from'  => ['sometimes', 'date_format:Y-m-d H:i:s|date_format:Y-m-d'],
            'created_to'    => ['sometimes', 'date_format:Y-m-d H:i:s|date_format:Y-m-d'],

            'resolved_from' => ['sometimes', 'date_format:Y-m-d H:i:s|date_format:Y-m-d'],
            'resolved_to'   => ['sometimes', 'date_format:Y-m-d H:i:s|date_format:Y-m-d'],

            'closed_from'   => ['sometimes', 'date_format:Y-m-d H:i:s|date_format:Y-m-d'],
            'closed_to'     => ['sometimes', 'date_format:Y-m-d H:i:s|date_format:Y-m-d'],
            'deleted'       => ['sometimes', 'in:true,only,with,false'],

            'sort_by'  => ['sometimes', 'in:created_at,ticket_number,updated_at,resolved_at,closed_at'],
            'sort_dir' => ['sometimes', 'in:asc,desc'],

            'per_page' => ['sometimes', 'integer', 'min:1', 'max:200'],
        ];
    }

    public function validatedWithDefaults(): array
    {
        $data = $this->validated();

        if (! isset($data['sort_by'])) {
            $data['sort_by'] = 'created_at';
        }

        if (! isset($data['sort_dir'])) {
            $data['sort_dir'] = 'desc';
        }

        if (! isset($data['per_page'])) {
            $data['per_page'] = 15;
        }

        return $data;
    }
}
