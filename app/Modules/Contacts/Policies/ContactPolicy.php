<?php

namespace App\Modules\Contacts\Policies;

use App\Modules\Contacts\Models\Contact;
use App\Modules\Users\Models\User;

class ContactPolicy
{
    public function viewAny(User $user): bool
    {
        if ($user->can('contacts.view.any') || $user->can('contacts.manage.any')) {
            return true;
        }
        if ($user->can('contacts.view.self')) {
            return true;
        }
        return false;
    }

    public function view(User $user, Contact $contact): bool
    {
        if ($user->can('contacts.view.any') || $user->can('contacts.manage.any')) {
            return true;
        }

        if ($user->can('contacts.view.self') && (string) $contact->owner_id === (string) $user->getKey()) {
            return true;
        }

        return false;
    }

    public function create(User $user): bool
    {
        if ($user->can('contacts.create.self') || $user->can('contacts.manage.any')) {
            return true;
        }
        return false;
    }

    public function update(User $user, Contact $contact): bool
    {
        return false;
    }

    public function delete(User $user, Contact $contact): bool
    {
        if ($user->can('contacts.delete.self') && (string) $contact->owner_id === (string) $user->getKey()) {
            return true;
        }
        if ($user->can('contacts.manage.any')) {
            return true;
        }
        return false;
    }

    public function restore(User $user, Contact $contact): bool
    {
        return false;
    }

    public function forceDelete(User $user, Contact $contact): bool
    {
        return false;
    }
}
