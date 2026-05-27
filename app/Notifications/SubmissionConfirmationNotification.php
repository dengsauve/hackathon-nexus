<?php

namespace App\Notifications;

use App\Models\ProjectEntry;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubmissionConfirmationNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly ProjectEntry $entry) {}

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
            ->subject('Project entry submitted')
            ->line($this->entry->title.' was submitted for '.$this->entry->event->name.'.')
            ->action('View portal', route('portal'));
    }
}
