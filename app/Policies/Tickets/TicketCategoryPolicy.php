<?php

namespace App\Policies\Tickets;

use App\Models\Tickets\TicketCategory;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TicketCategoryPolicy
{
    /**
    * Determine whether the user can view any models.
    */
    public function viewAny(User $user): bool
    {
        return $user->can('helpdesk.categories.view.any');
    }
    
    /**
    * Determine whether the user can view the model.
    */
    public function view(User $user, TicketCategory $ticketCategory): bool
    {
        return $user->can('helpdesk.categories.view.any');
    }
    
    /**
    * Determine whether the user can create models.
    */
    public function create(User $user): bool
    {
        return $user->can('helpdesk.categories.view.any');
    }
    
    /**
    * Determine whether the user can update the model.
    */
    public function update(User $user, TicketCategory $ticketCategory): bool
    {
        return $user->can('helpdesk.categories.update');
    }
    
    /**
    * Determine whether the user can delete the model.
    */
    public function delete(User $user, TicketCategory $ticketCategory): bool
    {
        return $user->can('helpdesk.categories.delete');
    }
    
    /**
    * Determine whether the user can restore the model.
    */
    public function restore(User $user, TicketCategory $ticketCategory): bool
    {
        return $user->can('helpdesk.categories.restore');  
    }
    
    /**
    * Determine whether the user can permanently delete the model.
    */
    public function forceDelete(User $user, TicketCategory $ticketCategory): bool
    {
        return $user->can('helpdesk.categories.forceDelete');
    }
}
