<?php

namespace App\Modules\Appointments\Policies;

use App\Modules\Appointments\Models\Appointment;
use App\Modules\Users\Models\User;
use Illuminate\Auth\Access\Response;

class AppointmentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('appointments.view.any')
            || $user->hasPermissionTo('appointments.manage.any')
            || $user->hasPermissionTo('appointments.moderate.any');
    }
    public function view(User $user, Appointment $appointment): bool
    {
        if (
            $user->hasPermissionTo('appointments.manage.any')
            || $user->hasPermissionTo('appointments.moderate.any')
            || $user->hasPermissionTo('appointments.view.any')
        ) {
            return true;
        }

        $isPatient = $appointment->user_id === $user->id;
        $isPsych   = $appointment->psychologist_id === $user->id;

        if ($isPatient && $user->hasPermissionTo('appointments.view.self')) {
            return true;
        }

        if ($isPsych && $user->hasPermissionTo('appointments.view.assigned')) {
            return true;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('appointments.create.self')
            || $user->hasPermissionTo('appointments.book')
            || $user->hasPermissionTo('appointments.manage.any')
            || $user->hasPermissionTo('appointments.moderate.any');
    }

    public function update(User $user, Appointment $appointment): bool
    {
        if (
            $user->hasPermissionTo('appointments.manage.any')
            || $user->hasPermissionTo('appointments.moderate.any')
        ) {
            return true;
        }

        $isPatient = $appointment->user_id === $user->id;

        if ($isPatient && $user->hasPermissionTo('appointments.update.self')) {
            return true;
        }

        return false;
    }

    public function delete(User $user, Appointment $appointment): bool
    {
        if ($user->hasPermissionTo('appointments.manage.any')) {
            return true;
        }

        $isPatient = $appointment->user_id === $user->id;

        if ($isPatient && $user->hasPermissionTo('appointments.delete.self')) {
            return true;
        }

        return false;
    }

    public function book(User $user): bool
    {
        return $user->hasPermissionTo('appointments.book')
            || $user->hasPermissionTo('appointments.create.self')
            || $user->hasPermissionTo('appointments.manage.any')
            || $user->hasPermissionTo('appointments.moderate.any');
    }

    public function cancel(User $user, Appointment $appointment): bool
    {
        if (
            $user->hasPermissionTo('appointments.manage.any')
            || $user->hasPermissionTo('appointments.moderate.any')
        ) {
            return true;
        }

        $isPatient = $appointment->user_id === $user->id;

        return $isPatient && $user->hasPermissionTo('appointments.cancel.self');
    }

    public function perform(User $user, Appointment $appointment): bool
    {
        if (
            $user->hasPermissionTo('appointments.manage.any')
            || $user->hasPermissionTo('appointments.moderate.any')
        ) {
            return true;
        }

        $isPsych = $appointment->psychologist_id === $user->id;

        return $isPsych && $user->hasPermissionTo('appointments.perform.assigned');
    }

    public function moderate(User $user): bool
    {
        return $user->hasPermissionTo('appointments.moderate.any')
            || $user->hasPermissionTo('appointments.manage.any');
    }

    public function manage(User $user): bool
    {
        return $user->hasPermissionTo('appointments.manage.any');
    }

    public function restore(User $user, Appointment $appointment): bool
    {
        return $this->manage($user);
    }

    public function forceDelete(User $user, Appointment $appointment): bool
    {
        return $this->manage($user);
    }
}
