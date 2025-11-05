<?php

namespace App\Modules\Audits\Observers;

use App\Modules\Audits\Services\AuditLogger;
use App\Modules\Users\Models\User;
use DateTimeInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class UserObserver
{
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

    private function extractValues(array $attributes): ?array
    {
        $values = Arr::only($attributes, self::AUDITABLE_ATTRIBUTES);

        return $this->normalizeValues($values);
    }

    private function normalizeValues(?array $values): ?array
    {
        if ($values === null) {
            return null;
        }

        $normalized = [];

        foreach ($values as $key => $value) {
            $normalizedValue = $this->normalizeValue($key, $value);

            if ($normalizedValue !== null || array_key_exists($key, $values)) {
                $normalized[$key] = $normalizedValue;
            }
        }

        return empty($normalized) ? null : $normalized;
    }

    private function normalizeValue(string $key, mixed $value): mixed
    {
        if ($value instanceof DateTimeInterface) {
            return $value->format(DateTimeInterface::ATOM);
        }

        if ($value === null || $value === '') {
            return $value;
        }

        if (is_string($value) && in_array($key, self::DATE_ATTRIBUTES, true)) {
            return Carbon::parse($value)->format(DateTimeInterface::ATOM);
        }

        if ($value instanceof \Stringable) {
            return (string) $value;
        }

        return $value;
    }
}
