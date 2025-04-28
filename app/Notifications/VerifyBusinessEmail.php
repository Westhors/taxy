<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Markdown;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerifyBusinessEmail extends Notification implements ShouldQueue
{
    use Queueable;

    public string $verificationCode;

    /**
     * Create a new notification instance.
     */
    public function __construct($verificationCode)
    {
        $this->verificationCode = $verificationCode;
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
            ->subject('Your Email Verification Code')
            ->greeting("Hello, {$notifiable->name}!")
            ->line('Your verification code is:')
            ->line(Markdown::parse('<span style="border: 1px solid #dfdfdf ;padding: 8px 16px; margin: auto; color: #303030; background-color: #eeeeee; border-radius: 8px; font-weight: bold">' . $this->verificationCode . '</span>'))
            ->line('This code will expire in 2 hours.')
            ->line('If you did not request this, please ignore this email.');
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
