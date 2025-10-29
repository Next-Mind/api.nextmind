<?php

use App\Modules\Appointments\Rules\ValidAppointmentStatusTransition;

it('allows valid appointment status transitions', function (?string $from, string $to) {
    $rule = new ValidAppointmentStatusTransition($from);

    $failCalled = false;

    $rule->validate('status', $to, function () use (&$failCalled) {
        $failCalled = true;
    });

    expect($failCalled)->toBeFalse();
})->with([
    'defaults from pending when null' => [null, 'scheduled'],
    'allows same status' => ['pending', 'pending'],
    'pending to canceled' => ['pending', 'canceled'],
    'scheduled to completed' => ['scheduled', 'completed'],
    'scheduled to canceled' => ['scheduled', 'canceled'],
]);

it('rejects invalid appointment status transitions', function (?string $from, string $to) {
    $rule = new ValidAppointmentStatusTransition($from);

    $message = null;

    $rule->validate('status', $to, function (string $msg) use (&$message) {
        $message = $msg;
    });

    $fromLabel = $from ?? 'pending';

    expect($message)->toBe("Transição de status {$fromLabel} → {$to} não é permitida.");
})->with([
    'pending to completed' => ['pending', 'completed'],
    'default pending to completed' => [null, 'completed'],
    'scheduled to pending' => ['scheduled', 'pending'],
    'canceled to scheduled' => ['canceled', 'scheduled'],
    'no show to completed' => ['no_show', 'completed'],
]);
