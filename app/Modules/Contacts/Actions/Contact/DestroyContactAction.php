<?php

namespace App\Modules\Contacts\Actions\Contact;

use App\Modules\Contacts\Models\Contact;
use App\Modules\Users\Models\User;
use App\Modules\Contacts\Exceptions\UnauthorizedToRemoveContactException;

class DestroyContactAction
{
    public function execute(User $owner, Contact $contact): void
    {
        if ((string) $contact->owner_id !== (string) $owner->getKey() && ! $owner->can('contacts.manage.any')) {
            throw new UnauthorizedToRemoveContactException();
        }

        $contact->delete();
    }
}
