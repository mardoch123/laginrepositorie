<?php

namespace App\Console\Commands;

use App\Models\Reminder;
use App\Events\ReminderTriggered;
use Illuminate\Console\Command;

class TestReminderNotification extends Command
{
    protected $signature = 'reminders:test-notification {reminder_id?}';
    protected $description = 'Teste l\'envoi de notification pour un rappel spécifique';

    public function handle()
    {
        $reminderId = $this->argument('reminder_id');
        
        if ($reminderId) {
            $reminder = Reminder::find($reminderId);
            if (!$reminder) {
                $this->error("Rappel avec l'ID {$reminderId} non trouvé.");
                return Command::FAILURE;
            }
        } else {
            // Prendre le premier rappel actif
            $reminder = Reminder::where('active', true)->first();
            if (!$reminder) {
                $this->error("Aucun rappel actif trouvé.");
                return Command::FAILURE;
            }
        }
        
        $this->info("Test de notification pour le rappel: {$reminder->title}");
        
        // Déclencher l'événement
        event(new ReminderTriggered($reminder));
        
        $this->info("Notification envoyée avec succès!");
        
        return Command::SUCCESS;
    }
}
