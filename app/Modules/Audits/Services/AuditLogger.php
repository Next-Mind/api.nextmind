<?php

namespace App\Modules\Audits\Services;

use App\Modules\Audits\Models\Audit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AuditLogger
{
    public function record(
        Model $auditable,
        string $event,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?array $extra = null
    ): void {
        $user = Auth::user();
        $request = request();

        Audit::query()->create([
            'event' => $event,
            'auditable_type' => $auditable->getMorphClass(),
            'auditable_id' => $auditable->getKey(),
            'user_type' => $user?->getMorphClass(),
            'user_id' => $user?->getKey(),
            'old_values' => $this->preparePayload($oldValues),
            'new_values' => $this->preparePayload($newValues),
            'extra' => $this->preparePayload($extra),
            'ip_address' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
        ]);
    }

    private function preparePayload(?array $values): ?array
    {
        return empty($values) ? null : $values;
    }
}
