<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendPackageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public $packageRecipient) {

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
    public function toMail($notifiable) {
        $url = url("/packages/view/{$this->packageRecipient->token}");

        return (new MailMessage)
            ->subject('You have received a new package')
            ->greeting("Hello {$notifiable->name},")
            ->line("You’ve been sent a package: {$this->packageRecipient->package->title}")
            ->action('View Package', $url)
            ->line("This link will expire on {$this->packageRecipient->expires_at->toDateString()}.");
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
