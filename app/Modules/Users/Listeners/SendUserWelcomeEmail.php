<?php

namespace App\Modules\Users\Listeners;

use App\Modules\Users\Mail\UserWelcomeMail;
use App\Modules\Users\Events\UserRegistered;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendUserWelcomeEmail implements ShouldQueue
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
    public function handle(UserRegistered $event): void
    {
        $user = $event->user;
        Mail::to($user->email)
            ->send(new UserWelcomeMail($user));
    }
}
