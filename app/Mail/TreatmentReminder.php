<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use App\Models\User;

class TreatmentReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $treatments;
    public $user;
    public $count;

    public function __construct(Collection $treatments, User $user)
    {
        $this->treatments = $treatments;
        $this->user = $user;
        $this->count = $treatments->count();
    }

    public function build()
    {
        return $this->subject('RAPPEL: ' . $this->count . ' traitement(s) à effectuer dans les 3 prochaines heures')
                    ->markdown('emails.treatments.reminder');
    }
}