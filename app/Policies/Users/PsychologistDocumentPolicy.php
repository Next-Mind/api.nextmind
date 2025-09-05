<?php

namespace App\Policies\Users;

use App\Models\User;
use App\Models\Users\PsychologistDocument;

class PsychologistDocumentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PsychologistDocument $psychologistDocument): bool
    {
        if($user->can('users.manage') && $user->can('files.manage.any')) {
            return true;
        }
        if($user->can('files.manage.self') && $user->psychologistProfile->id === $psychologistDocument->psychologist_profile_id) {
            return true;
        }
        
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if($user->psychologistProfile?->status == 'pending' || $user->psychologistProfile?->status == 'rejected') {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PsychologistDocument $psychologistDocument): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PsychologistDocument $psychologistDocument): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PsychologistDocument $psychologistDocument): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PsychologistDocument $psychologistDocument): bool
    {
        return false;
    }
}
