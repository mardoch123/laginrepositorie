<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        // Exécuter la commande chaque dimanche à minuit
        $schedule->command('food:generate-schedule')
                 ->weeklyOn(0, '00:00')
                 ->appendOutputTo(storage_path('logs/food-schedule.log'));
        $schedule->command('breedings:check-upcoming')->dailyAt('08:00');
        $schedule->command('reminders:check')->everyFiveMinutes();
        $schedule->command('treatments:send-reminders')->hourly();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
