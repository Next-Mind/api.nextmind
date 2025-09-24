<?php

namespace App\Policies\Appointments;

use App\Models\Appointments\Availability;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AvailabilityPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Availability $availability): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('psychologist') || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Availability $availability): bool
    {
        return $user->id === $availability->psychologist_id || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Availability $availability): bool
    {
        return $user->id === $availability->psychologist_id || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Availability $availability): bool
    {
        return $user->id === $availability->psychologist_id || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Availability $availability): bool
    {
        return $user->id === $availability->psychologist_id || $user->hasRole('admin');
    }
}
