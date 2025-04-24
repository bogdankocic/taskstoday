<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class UserInviteNotification extends Notification
{
    use Queueable;

    protected User $user;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
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
        $signedUrl = URL::signedRoute('activate-user', [
            'user_id' => $this->user->id,
        ]);

        return (new MailMessage)
            ->subject('Activate Your Account')
            ->line('You have been invited to join our platform.')
            ->action('Activate Account', $signedUrl)
            ->line('Thank you for using our application!');
    }
}
