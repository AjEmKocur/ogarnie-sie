<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as BaseVerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmailNotification extends BaseVerifyEmail
{
    /**
     * Build the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Potwierdź adres e-mail')
            ->greeting('Cześć!')
            ->line('Dziękujemy za rejestrację w serwisie Kocur Serwis Komputerowy.')
            ->line('Aby aktywować konto, potwierdź swój adres e-mail.')
            ->action('Potwierdź e-mail', $verificationUrl)
            ->line('Jeśli to nie Ty zakładałeś konto, zignoruj tę wiadomość.');
    }
}
