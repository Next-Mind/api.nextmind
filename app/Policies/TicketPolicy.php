<?php

namespace App\Policies;

use App\Models\Tickets\Ticket;
use App\Models\User;

class TicketPolicy
{
    /**
     * User can list tickets at all?
     *
     * Precisa ter pelo menos uma dessas:
     * - helpdesk.tickets.view.any
     * - helpdesk.tickets.view.self
     */
    public function viewAny(User $user): bool
    {
        return
            $user->can('helpdesk.tickets.view.any') ||
            $user->can('helpdesk.tickets.view.self');
    }

    /**
     * User can see a specific ticket?
     *
     * - Se tiver helpdesk.tickets.view.any => sim pra qualquer ticket
     * - Senão, precisa ter helpdesk.tickets.view.self E ser dono do ticket
     */
    public function view(User $user, Ticket $ticket): bool
    {
        if ($user->can('helpdesk.tickets.view.any')) {
            return true;
        }

        if (
            $user->can('helpdesk.tickets.view.self') &&
            $ticket->user_id === $user->id
        ) {
            return true;
        }

        return false;
    }

    /**
     * User pode criar ticket?
     *
     * - Precisa de helpdesk.tickets.create
     */
    public function create(User $user): bool
    {
        return $user->can('helpdesk.tickets.create');
    }

    /**
     * User pode atualizar um ticket?
     *
     * - Se tiver helpdesk.tickets.update:
     *    - e também helpdesk.tickets.view.any => pode editar qualquer um
     *    - ou for dono do ticket
     *
     * (isso evita que alguém com "update" mas sem permissão de ver todos
     * consiga editar ticket de outra pessoa)
     */
    public function update(User $user, Ticket $ticket): bool
    {
        if (! $user->can('helpdesk.tickets.update')) {
            return false;
        }

        if ($user->can('helpdesk.tickets.view.any')) {
            return true;
        }

        return $ticket->user_id === $user->id;
    }

    /**
     * User pode deletar?
     *
     * - Precisa de helpdesk.tickets.delete
     *
     * Se você quiser restringir "só dono pode deletar a si mesmo"
     * é só trocar o return por mesma lógica do update().
     */
    public function delete(User $user, Ticket $ticket): bool
    {
        return $user->can('helpdesk.tickets.delete');
    }

    /**
     * restore / forceDelete:
     * você não listou permissões pra isso.
     * Vou só espelhar a mesma regra do delete
     * pra não deixar buraco no Laravel.
     */

    public function restore(User $user, Ticket $ticket): bool
    {
        return $user->can('helpdesk.tickets.delete');
    }

    public function forceDelete(User $user, Ticket $ticket): bool
    {
        return $user->can('helpdesk.tickets.delete');
    }
}
