<?php

namespace App\Providers;

use App\Policies\Psychologists\PsychologistDocumentPolicy;
use App\Policies\Users\UserAddressPolicy;
use App\Policies\Users\UserPhonePolicy;
use Illuminate\Support\Facades\Gate;
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

        VerifyEmail::toMailUsing(function (object $notifiable,string $url){
            return (new MailMessage)
                ->subject('Verifique seu e-mail')
                ->markdown('emails.verifyEmail', ['url' => $url, 'user' => $notifiable]);
        });
    }
}
