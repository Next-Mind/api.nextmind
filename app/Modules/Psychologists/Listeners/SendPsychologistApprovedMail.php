<?php

namespace App\Modules\Psychologists\Listeners;

use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use App\Modules\Psychologists\Events\PsychologistProfileApproved;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Modules\Psychologists\Mail\PsychologistProfileApprovedMail;

class SendPsychologistApprovedMail implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PsychologistProfileApproved $event): void
    {
        $user = $event->profile->user;
        Mail::to($user->email)->send(new PsychologistProfileApprovedMail($event->profile));
    }
}
