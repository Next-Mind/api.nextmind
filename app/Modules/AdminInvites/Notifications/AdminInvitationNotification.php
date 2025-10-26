<?php

namespace App\Modules\AdminInvites\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminInvitationNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public string $inviteeEmail,
        public string $acceptUrl,
        public string $declineUrl,
        public string $inviterName
    ) {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Convite para ser administrador')
            ->greeting('Olá!')
            ->line("Você foi convidado(a) por {$this->inviterName} para ser administrador da plataforma.")
            ->line('Você pode aceitar ou recusar o convite usando os botões abaixo.')
            ->action('Aceitar convite', $this->acceptUrl)
            ->line("Se você não deseja aceitar, clique aqui para recusar: {$this->declineUrl}")
            ->line('Se você não esperava este e-mail, pode ignorá-lo.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
