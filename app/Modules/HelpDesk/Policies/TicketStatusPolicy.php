<?php

namespace App\Modules\HelpDesk\Policies;

use App\Modules\HelpDesk\Models\TicketStatus;
use App\Modules\Users\Models\User;
use Illuminate\Auth\Access\Response;

class TicketStatusPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('helpdesk.status.view.any');
    }

    public function view(User $user, TicketStatus $ticketStatus): bool
    {
        return $user->can('helpdesk.status.view.any');
    }

    public function create(User $user): bool
    {
        return $user->can('helpdesk.status.create');
    }

    public function update(User $user, TicketStatus $ticketStatus): bool
    {
        return $user->can('helpdesk.status.update');
    }

    public function delete(User $user, TicketStatus $ticketStatus): bool
    {
        return $user->can('helpdesk.status.delete');
    }

    public function restore(User $user, TicketStatus $ticketStatus): bool
    {
        return $user->can('helpdesk.status.delete');
    }

    public function forceDelete(User $user, TicketStatus $ticketStatus): bool
    {
        return $user->can('helpdesk.status.delete');
    }
}
