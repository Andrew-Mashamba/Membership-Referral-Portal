<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewUserCredentialsNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $temporaryPassword,
        public string $setPasswordUrl
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $appName = config('app.name');

        return (new MailMessage)
            ->subject("Your {$appName} account")
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('An administrator has created an account for you.')
            ->line('**Login credentials:**')
            ->line('Email: ' . $notifiable->email)
            ->line('Temporary password: ' . $this->temporaryPassword)
            ->line('Use the button below to set your own password, or sign in with the temporary password at ' . url('/login') . ' and change it in your profile.')
            ->action('Set your password', $this->setPasswordUrl)
            ->line('If you did not expect this email, you can ignore it.');
    }
}
