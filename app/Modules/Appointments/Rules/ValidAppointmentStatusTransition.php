<?php

namespace App\Modules\Appointments\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidAppointmentStatusTransition implements ValidationRule
{
    public function __construct(private ?string $fromStatus) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $from = $this->fromStatus ?? 'pending';
        $to   = (string) $value;

        $allowed = [
            'pending'   => ['scheduled', 'canceled'],
            'scheduled' => ['completed', 'canceled', 'no_show'],
            'completed' => [],
            'canceled'  => [],
            'no_show'   => [],
        ];

        // mesmo status é ok
        if ($from === $to) {
            return;
        }

        if (!in_array($to, $allowed[$from] ?? [], true)) {
            $fail("Transição de status {$from} → {$to} não é permitida.");
        }
    }
}
