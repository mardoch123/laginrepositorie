<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Breeding;
use Carbon\Carbon;
use App\Events\BirthReminder;

class CheckUpcomingBirths extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'breedings:check-upcoming';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for upcoming births and send reminders';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $threeDaysFromNow = Carbon::now()->addDays(3)->startOfDay();
        
        $upcomingBirths = Breeding::with('mother')
            ->whereNull('actual_birth_date')
            ->whereDate('expected_birth_date', $threeDaysFromNow)
            ->get();
            
        foreach ($upcomingBirths as $breeding) {
            event(new BirthReminder($breeding));
            $this->info("Sent reminder for breeding ID: {$breeding->id}");
        }
        
        $this->info("Found {$upcomingBirths->count()} upcoming births.");
        
        return Command::SUCCESS;
    }
}