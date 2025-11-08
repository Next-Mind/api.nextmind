<?php

namespace App\Modules\Audits\Observers;

use App\Modules\Appointments\Models\Availability;
use App\Modules\Audits\Services\AuditLogger;
use Illuminate\Support\Arr;

class AvailabilityObserver
{
    use AuditValueNormalizer;

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
}
