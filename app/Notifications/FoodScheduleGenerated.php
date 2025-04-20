<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class FoodScheduleGenerated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $schedules;

    public function __construct($schedules)
    {
        $this->schedules = $schedules;
    }

    public function via($notifiable)
    {
        return ['database', 'mail', WebPushChannel::class];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Nouvel emploi du temps des nourritures généré')
            ->line('L\'emploi du temps des nourritures pour la semaine a été généré.')
            ->action('Voir l\'emploi du temps', url('/food-schedules'))
            ->line('Merci d\'utiliser notre application!');
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Nouvel emploi du temps des nourritures',
            'message' => 'L\'emploi du temps des nourritures pour la semaine a été généré.',
            'action_url' => '/food-schedules',
            'schedules_count' => count($this->schedules),
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Nouvel emploi du temps des nourritures')
            ->icon('/notification-icon.png')
            ->body('L\'emploi du temps des nourritures pour la semaine a été généré.')
            ->action('Voir', '/food-schedules');
    }
}