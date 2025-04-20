<?php

namespace App\Listeners;

use App\Events\ReminderTriggered;
use App\Models\User;
use App\Notifications\ReminderNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendReminderNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(ReminderTriggered $event)
    {
        // Récupérer tous les utilisateurs qui doivent recevoir la notification
        $users = User::all();
        
        foreach ($users as $user) {
            $user->notify(new ReminderNotification($event->reminder));
        }
    }
}