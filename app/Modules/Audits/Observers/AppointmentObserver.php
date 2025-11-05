<?php

namespace App\Modules\Audits\Observers;

use App\Modules\Appointments\Models\Appointment;
use App\Modules\Audits\Services\AuditLogger;
use Illuminate\Support\Arr;

class AppointmentObserver
{
    use AuditValueNormalizer;

    private const AUDITABLE_ATTRIBUTES = [
        'id',
        'availability_id',
        'user_id',
        'psychologist_id',
        'status',
        'description',
        'created_at',
        'updated_at',
    ];

    private const DATE_ATTRIBUTES = [
        'created_at',
        'updated_at',
    ];

    public function __construct(private readonly AuditLogger $logger)
    {
    }

    public function created(Appointment $appointment): void
    {
        $newValues = $this->extractValues($appointment->getAttributes());

        $this->logger->record($appointment, 'appointments.created', null, $newValues);
    }

    public function updated(Appointment $appointment): void
    {
        $changes = Arr::only($appointment->getChanges(), self::AUDITABLE_ATTRIBUTES);
        unset($changes['updated_at']);

        if (empty($changes)) {
            return;
        }

        $oldValues = [];
        $newValues = [];

        foreach (array_keys($changes) as $attribute) {
            $oldValues[$attribute] = $appointment->getOriginal($attribute);
            $newValues[$attribute] = $appointment->getAttribute($attribute);
        }

        $this->logger->record(
            $appointment,
            'appointments.updated',
            $this->normalizeValues($oldValues),
            $this->normalizeValues($newValues)
        );
    }

    public function deleted(Appointment $appointment): void
    {
        $oldValues = $this->extractValues($appointment->getOriginal());

        $this->logger->record($appointment, 'appointments.deleted', $oldValues);
    }
}
