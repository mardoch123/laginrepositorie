<?php

namespace App\Console\Commands;

use App\Models\Treatment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\TreatmentReminder;

class SendTreatmentReminders extends Command
{
    protected $signature = 'treatments:send-reminders';
    protected $description = 'Send email reminders for treatments due in the next 3 hours';

    public function handle()
    {
        // Find treatments scheduled in the next 3 hours
        $now = Carbon::now();
        $threeHoursLater = $now->copy()->addHours(3);
        
        $upcomingTreatments = Treatment::with(['rabbit', 'medication'])
            ->where('status', 'pending')
            ->whereBetween('scheduled_at', [$now, $threeHoursLater])
            ->get();
            
        if ($upcomingTreatments->isEmpty()) {
            $this->info('No upcoming treatments found.');
            return 0;
        }
        
        // Get all users to notify
        $users = User::whereNotNull('email')->get();
        
        if ($users->isEmpty()) {
            $this->error('No users with email addresses found.');
            return 1;
        }
        
        // Send notifications
        foreach ($users as $user) {
            Mail::to($user->email)->send(new TreatmentReminder($upcomingTreatments, $user));
        }
        
        $this->info('Sent ' . count($users) . ' email reminders for ' . count($upcomingTreatments) . ' upcoming treatments.');
        return 0;
    }
}