<?php

namespace App\Notifications;

use App\Models\AssistanceRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AssistanceRequestNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly AssistanceRequest $assistanceRequest) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New assistance request')
            ->line($this->assistanceRequest->subject)
            ->line($this->assistanceRequest->message)
            ->action('Manage event', route('manage.events.show', $this->assistanceRequest->event));
    }
}
