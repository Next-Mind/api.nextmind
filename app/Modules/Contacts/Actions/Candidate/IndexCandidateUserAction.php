<?php

namespace App\Modules\Contacts\Actions\Candidate;

use App\Modules\Contacts\Models\Contact;
use App\Modules\Users\Models\User;

class IndexCandidateUserAction
{
    public function execute(User $requester, array $queryParams = [])
    {
        $term = trim((string) ($queryParams['search'] ?? ''));

        $query = User::query()->select(['id', 'name', 'photo_url']);

        if ($requester->hasRole('student')) {
            $query->role('psychologist');
        } elseif ($requester->hasRole('psychologist')) {
            // can list all users
        } elseif (! $requester->can('contacts.manage.any')) {
            // fallback: only self
            $query->where('id', $requester->getKey());
        }

        $query->where('id', '!=', $requester->getKey());

        $query->whereNotIn('id', Contact::query()
            ->select('contact_id')
            ->where('owner_id', $requester->getKey())
        );

        if ($term !== '') {
            $like = "%{$term}%";
            $query->where('name', 'like', $like);
        }

        return $query->latest()->simplePaginate(15);
    }
}

