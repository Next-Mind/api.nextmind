<?php

namespace App\Modules\Contacts\Actions\Contact;

use App\Modules\Contacts\Models\Contact;
use App\Modules\Users\Models\User;

class IndexContactAction
{
    public function execute(User $owner)
    {
        $query = Contact::query()
            ->where('owner_id', $owner->getKey())
            ->with(['contactUser:id,name,photo_url']);

        return $query->latest()->simplePaginate(15);
    }
}

