<?php

namespace App\Modules\HelpDesk\Actions\Ticket;

use App\Modules\Users\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Modules\HelpDesk\Models\Ticket;

class IndexTicketAction
{
    public function execute(array $queryParams, ?User $user = null, array $relations)
    {
        $user ??= Auth::user();


        $query = Ticket::query();

        if (!empty($relations)) {
            $query->with($relations);
        }

        if (! $user->can('helpdesk.tickets.view.any')) {
            $query->where('requester_id', $user->id);
        } else {
            if (!empty($queryParams['user_id'])) {
                $query->where('requester_id', $queryParams['user_id']);
            }
        }

        if (!empty($queryParams['ticket_number'])) {
            $query->where('ticket_number', $queryParams['ticket_number']);
        }

        if (!empty($queryParams['subject'])) {
            $query->where('subject', 'like', '%' . $queryParams['subject'] . '%');
        }

        if (!empty($queryParams['opened_by_id'])) {
            $query->where('opened_by_id', $queryParams['opened_by_id']);
        }

        if (!empty($queryParams['requester_id'])) {
            $query->where('requester_id', $queryParams['requester_id']);
        }

        if (!empty($queryParams['assigned_to_id'])) {
            $query->where('assigned_to_id', $queryParams['assigned_to_id']);
        }

        if (!empty($queryParams['ticket_category_id'])) {
            $query->where('ticket_category_id', $queryParams['ticket_category_id']);
        }

        if (!empty($queryParams['ticket_subcategory_id'])) {
            $query->where('ticket_subcategory_id', $queryParams['ticket_subcategory_id']);
        }

        if (!empty($queryParams['ticket_status_id'])) {
            $query->where('ticket_status_id', $queryParams['ticket_status_id']);
        }

        if (!empty($queryParams['created_from'])) {
            $query->where('created_at', '>=', $queryParams['created_from']);
        }

        if (!empty($queryParams['created_to'])) {
            $query->where('created_at', '<=', $queryParams['created_to']);
        }

        if (!empty($queryParams['resolved_from'])) {
            $query->where('resolved_at', '>=', $queryParams['resolved_from']);
        }

        if (!empty($queryParams['resolved_to'])) {
            $query->where('resolved_at', '<=', $queryParams['resolved_to']);
        }

        if (!empty($queryParams['closed_from'])) {
            $query->where('closed_at', '>=', $queryParams['closed_from']);
        }

        if (!empty($queryParams['closed_to'])) {
            $query->where('closed_at', '<=', $queryParams['closed_to']);
        }

        if (!empty($queryParams['deleted'])) {
            if ($queryParams['deleted'] === 'true' || $queryParams['deleted'] === 'only') {
                $query->onlyTrashed();
            } elseif ($queryParams['deleted'] === 'with') {
                $query->withTrashed();
            }
        }

        $allowedSorts = [
            'created_at',
            'ticket_number',
            'updated_at',
            'resolved_at',
            'closed_at',
        ];

        $sortBy  = $queryParams['sort_by']  ?? 'created_at';
        $sortDir = $queryParams['sort_dir'] ?? 'desc';

        if (! in_array($sortBy, $allowedSorts, true)) {
            $sortBy = 'created_at';
        }

        if (! in_array(strtolower($sortDir), ['asc', 'desc'], true)) {
            $sortDir = 'desc';
        }

        $query->orderBy($sortBy, $sortDir);

        $perPage = !empty($queryParams['per_page'])
            ? (int) $queryParams['per_page']
            : 15;

        return $query->simplePaginate($perPage);
    }
}
