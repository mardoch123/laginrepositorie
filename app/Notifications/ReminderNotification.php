<?php

namespace App\Notifications;

use App\Models\Reminder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class ReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $reminder;

    public function __construct(Reminder $reminder)
    {
        $this->reminder = $reminder;
    }

    public function via($notifiable)
    {
        return [WebPushChannel::class];
    }

    public function toWebPush($notifiable, $notification)
    {
        $priorityEmoji = '';
        switch ($this->reminder->priority ?? 'medium') {
            case 'low':
                $priorityEmoji = '🟢';
                break;
            case 'medium':
                $priorityEmoji = '🟡';
                break;
            case 'high':
                $priorityEmoji = '🟠';
                break;
            case 'urgent':
                $priorityEmoji = '🔴';
                break;
        }

        return (new WebPushMessage)
            ->title($priorityEmoji . ' Rappel: ' . $this->reminder->title)
            ->icon('/images/icon-192x192.png')
            ->body($this->reminder->description ?? 'Cliquez pour voir les détails')
            ->action('Voir', 'view')
            ->badge('/images/badge.png')
            ->dir('auto')
            ->data(['url' => url('/reminders/' . $this->reminder->id)]);
    }
}