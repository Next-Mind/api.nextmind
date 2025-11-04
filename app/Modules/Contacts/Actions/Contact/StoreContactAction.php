<?php

namespace App\Modules\Contacts\Actions\Contact;

use App\Modules\Contacts\Models\Contact;
use App\Modules\Users\Models\User;
use App\Modules\Contacts\Exceptions\CannotAddSelfAsContactException;
use App\Modules\Contacts\Exceptions\StudentCanOnlyAddPsychologistException;
use App\Modules\Contacts\Exceptions\ContactAlreadyExistsException;
use App\Modules\Contacts\Exceptions\UnauthorizedToAddContactException;

class StoreContactAction
{
    public function execute(User $owner, User $target): Contact
    {
        if ((string) $owner->getKey() === (string) $target->getKey()) {
            throw new CannotAddSelfAsContactException();
        }

        $isStudent = $owner->hasRole('student');
        $isPsychologist = $owner->hasRole('psychologist');

        if ($isStudent && ! $target->hasRole('psychologist')) {
            throw new StudentCanOnlyAddPsychologistException();
        }

        if (! $isStudent && ! $isPsychologist && ! $owner->can('contacts.manage.any')) {
            throw new UnauthorizedToAddContactException();
        }

        $exists = Contact::query()
            ->where('owner_id', $owner->getKey())
            ->where('contact_id', $target->getKey())
            ->exists();

        if ($exists) {
            throw new ContactAlreadyExistsException();
        }

        return Contact::create([
            'owner_id' => $owner->getKey(),
            'contact_id' => $target->getKey(),
        ]);
    }
}
