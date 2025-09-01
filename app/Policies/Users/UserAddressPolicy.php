<?php

namespace App\Policies\Users;

use App\Models\User;
use App\Models\Users\UserAddress;
use Illuminate\Auth\Access\Response;

class UserAddressPolicy
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
    public function view(User $user, UserAddress $userAddress): bool
    {
       if($user->can('profiles.view.self') && $user->id == $userAddress->user_id) {
            return true;
        }

        if($user->can('profiles.view.any')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
         if($user->can('profiles.update.self') || $user->can('profiles.update.any')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, UserAddress $userAddress): bool
    {
        if($user->can('profiles.update.self') && $user->id == $userAddress->user_id) {
            return true;
        }

        if($user->can('profiles.update.any')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, UserAddress $userAddress): bool
    {
       if($user->can('profiles.update.self') && $user->id == $userAddress->user_id) {
            return true;
        }

        if($user->can('profiles.update.any')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, UserAddress $userAddress): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, UserAddress $userAddress): bool
    {
        return false;
    }
}
