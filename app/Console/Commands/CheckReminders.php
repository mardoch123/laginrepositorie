<?php

namespace App\Console\Commands;

use App\Models\Reminder;
use App\Models\ReminderLog;
use Illuminate\Console\Command;
use App\Events\ReminderTriggered;

class CheckReminders extends Command
{
    protected $signature = 'reminders:check';
    protected $description = 'Vérifie et exécute les rappels programmés';

    public function handle()
    {
        $this->info('Vérification des rappels...');
        
        $reminders = Reminder::where('active', true)->get();
        $count = 0;
        
        foreach ($reminders as $reminder) {
            if ($reminder->shouldExecute()) {
                // Enregistrer l'exécution
                $log = ReminderLog::create([
                    'reminder_id' => $reminder->id,
                    'executed_at' => now(),
                    'success' => true
                ]);
                
                // Mettre à jour la date de dernière exécution
                $reminder->update(['last_executed' => now()]);
                
                // Déclencher l'événement pour les notifications
                event(new ReminderTriggered($reminder));
                
                $count++;
                $this->info("Rappel exécuté: {$reminder->title}");
            }
        }
        
        $this->info("Terminé. {$count} rappels exécutés.");
        
        return Command::SUCCESS;
    }
}