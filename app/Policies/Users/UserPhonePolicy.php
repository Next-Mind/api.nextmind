<?php

namespace App\Policies\Users;

use App\Models\User;
use App\Models\Users\UserPhone;
use Illuminate\Auth\Access\Response;

class UserPhonePolicy
{
    /**
    * Determine whether the user can view any models.
    */
    public function viewAny(User $actor, User $owner): bool
    {
        if ($actor->can('profiles.view.any')) {
            return true; // admin pode listar de qualquer user
        }
        
        return $actor->can('profiles.view.self') && $actor->is($owner);
    }
    
    /**
    * Determine whether the user can view the model.
    */
    public function view(User $user, UserPhone $userPhone): bool
    {
        if($user->can('profiles.view.self') && $user->id == $userPhone->user_id) {
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
    public function update(User $user, UserPhone $userPhone): bool
    {
        if($user->can('profiles.update.self') && $user->id == $userPhone->user_id) {
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
    public function delete(User $user, UserPhone $userPhone): bool
    {
        if($user->can('profiles.update.self') && $user->id == $userPhone->user_id) {
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
    public function restore(User $user, UserPhone $userPhone): bool
    {
        return false;
    }
    
    /**
    * Determine whether the user can permanently delete the model.
    */
    public function forceDelete(User $user, UserPhone $userPhone): bool
    {
        return false;
    }
}
