<?php

namespace App\Modules\Audits\Observers;

use App\Modules\Appointments\Models\Availability;
use App\Modules\Audits\Services\AuditLogger;
use DateTimeInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class AvailabilityObserver
{
    private const AUDITABLE_ATTRIBUTES = [
        'id',
        'user_id',
        'reserved_by',
        'date_availability',
        'status',
        'created_at',
        'updated_at',
    ];

    private const DATE_ATTRIBUTES = [
        'date_availability',
        'created_at',
        'updated_at',
    ];

    public function __construct(private readonly AuditLogger $logger)
    {
    }

    public function created(Availability $availability): void
    {
        $newValues = $this->extractValues($availability->getAttributes());

        $this->logger->record($availability, 'availabilities.created', null, $newValues);
    }

    public function updated(Availability $availability): void
    {
        $changes = Arr::only($availability->getChanges(), self::AUDITABLE_ATTRIBUTES);
        unset($changes['updated_at']);

        if (empty($changes)) {
            return;
        }

        $oldValues = [];
        $newValues = [];

        foreach (array_keys($changes) as $attribute) {
            $oldValues[$attribute] = $availability->getOriginal($attribute);
            $newValues[$attribute] = $availability->getAttribute($attribute);
        }

        $this->logger->record(
            $availability,
            'availabilities.updated',
            $this->normalizeValues($oldValues),
            $this->normalizeValues($newValues)
        );
    }

    public function deleted(Availability $availability): void
    {
        $oldValues = $this->extractValues($availability->getOriginal());

        $this->logger->record($availability, 'availabilities.deleted', $oldValues);
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
