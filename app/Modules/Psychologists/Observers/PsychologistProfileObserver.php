<?php

namespace App\Modules\Psychologists\Observers;

use App\Modules\Psychologists\Models\PsychologistProfile;
use App\Modules\Psychologists\Events\PsychologistProfileApproved;

class PsychologistProfileObserver
{
    /**
     * Handle the PsychologistProfile "created" event.
     */
    public function created(PsychologistProfile $psychologistProfile): void
    {
        //
    }

    /**
     * Handle the PsychologistProfile "updated" event.
     */
    public function updated(PsychologistProfile $psychologistProfile): void
    {

        if (
            $psychologistProfile->wasChanged('status') &&
            $psychologistProfile->status === PsychologistProfile::STATUS_APPROVED &&
            $psychologistProfile->getOriginal('status') !== PsychologistProfile::STATUS_APPROVED
        ) {
            PsychologistProfileApproved::dispatch($psychologistProfile);
        }
    }

    /**
     * Handle the PsychologistProfile "deleted" event.
     */
    public function deleted(PsychologistProfile $psychologistProfile): void
    {
        //
    }

    /**
     * Handle the PsychologistProfile "restored" event.
     */
    public function restored(PsychologistProfile $psychologistProfile): void
    {
        //
    }

    /**
     * Handle the PsychologistProfile "force deleted" event.
     */
    public function forceDeleted(PsychologistProfile $psychologistProfile): void
    {
        //
    }
}
