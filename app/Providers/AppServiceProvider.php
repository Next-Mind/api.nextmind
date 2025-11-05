<?php

namespace App\Providers;

use App\Modules\Audits\Observers\UserObserver;
use App\Modules\Psychologists\Models\PsychologistProfile;
use App\Modules\Psychologists\Observers\PsychologistProfileObserver;
use App\Modules\Users\Models\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //Event::listen(UserRegistered::class,SendUserWelcomeEmail::class);

        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->subject('Verifique seu e-mail')
                ->markdown('emails.verifyEmail', ['url' => $url, 'user' => $notifiable]);
        });

        PsychologistProfile::observe(PsychologistProfileObserver::class);
        User::observe(UserObserver::class);
    }
}
