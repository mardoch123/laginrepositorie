<?php

namespace App\Events;

use App\Models\Reminder;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

// Retirez l'implémentation de ShouldBroadcast pour désactiver temporairement la diffusion
class ReminderTriggered
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $reminder;

    public function __construct(Reminder $reminder)
    {
        $this->reminder = $reminder;
    }

    // Vous pouvez conserver ces méthodes pour une utilisation future
    // mais elles ne seront pas utilisées tant que vous n'implémentez pas ShouldBroadcast
    public function broadcastOn()
    {
        return new Channel('reminders');
    }

    public function broadcastAs()
    {
        return 'reminder.triggered';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'id' => $this->reminder->id,
            'title' => $this->reminder->title,
            'description' => $this->reminder->description,
            'time' => $this->reminder->time ? $this->reminder->time->format('H:i') : null,
            'timestamp' => now()->toIso8601String()
        ];
    }
}