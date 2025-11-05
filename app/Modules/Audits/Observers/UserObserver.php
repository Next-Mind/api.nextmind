<?php

namespace App\Modules\Audits\Observers;

use App\Modules\Audits\Services\AuditLogger;
use App\Modules\Users\Models\User;
use Illuminate\Support\Arr;

class UserObserver
{
    use AuditValueNormalizer;

    private const AUDITABLE_ATTRIBUTES = [
        'name',
        'email',
        'cpf',
        'birth_date',
        'photo_url',
        'email_verified_at',
    ];

    private const DATE_ATTRIBUTES = [
        'birth_date',
        'email_verified_at',
    ];

    public function __construct(private readonly AuditLogger $logger)
    {
    }

    public function created(User $user): void
    {
        $newValues = $this->extractValues($user->getAttributes());

        $this->logger->record($user, 'users.created', null, $newValues);
    }

    public function updated(User $user): void
    {
        $this->recordEmailVerificationIfNeeded($user);

        $changes = Arr::only($user->getChanges(), self::AUDITABLE_ATTRIBUTES);
        unset($changes['email_verified_at']);

        if (empty($changes)) {
            return;
        }

        $oldValues = [];
        $newValues = [];

        foreach (array_keys($changes) as $attribute) {
            $oldValues[$attribute] = $user->getOriginal($attribute);
            $newValues[$attribute] = $user->getAttribute($attribute);
        }

        $this->logger->record(
            $user,
            'users.updated',
            $this->normalizeValues($oldValues),
            $this->normalizeValues($newValues)
        );
    }

    private function recordEmailVerificationIfNeeded(User $user): void
    {
        if (! $user->wasChanged('email_verified_at')) {
            return;
        }

        $old = $user->getOriginal('email_verified_at');
        $new = $user->email_verified_at;

        $event = $user->email_verified_at ? 'users.email_verified' : 'users.email_unverified';

        $this->logger->record(
            $user,
            $event,
            $this->normalizeValues(['email_verified_at' => $old]),
            $this->normalizeValues(['email_verified_at' => $new])
        );
    }
}
