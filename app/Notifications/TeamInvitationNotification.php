<?php

namespace App\Notifications;

use App\Models\TeamInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TeamInvitationNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly TeamInvitation $invitation) {}

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
            ->subject('You were invited to join '.$this->invitation->team->name)
            ->line('You were invited to join '.$this->invitation->team->name.' on Hackathon Nexus.')
            ->line('Role: '.$this->invitation->role)
            ->action('View invitation', route('team-invitations.show', $this->invitation->token))
            ->line('This invitation expires '.$this->invitation->expires_at->toDayDateTimeString().'.');
    }
}
