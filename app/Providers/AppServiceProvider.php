<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use App\Policies\Users\UserPhonePolicy;
use Illuminate\Support\ServiceProvider;
use App\Models\Users\PsychologistProfile;
use App\Policies\Users\UserAddressPolicy;
use App\Observers\PsychologistProfileObserver;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use App\Policies\Psychologists\PsychologistDocumentPolicy;

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

        VerifyEmail::toMailUsing(function (object $notifiable,string $url){
            return (new MailMessage)
                ->subject('Verifique seu e-mail')
                ->markdown('emails.verifyEmail', ['url' => $url, 'user' => $notifiable]);
        });

         PsychologistProfile::observe(PsychologistProfileObserver::class);
    }
}
