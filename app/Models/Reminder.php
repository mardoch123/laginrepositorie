<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'due_date',
        'is_completed',
        'priority',
        'rabbit_id',
        'litter_id',
        'frequency',
        'time',
        'days_of_week',
        'interval_days',
        'last_executed',
        'active'
    ];

    protected $casts = [
        'days_of_week' => 'array',
        'time' => 'datetime:H:i',
        'last_executed' => 'datetime',
        'due_date' => 'date',
        'is_completed' => 'boolean',
        'active' => 'boolean',
    ];

    public function rabbit()
    {
        return $this->belongsTo(Rabbit::class);
    }

    public function litter()
    {
        return $this->belongsTo(Litter::class);
    }

    public function logs()
    {
        return $this->hasMany(ReminderLog::class);
    }

    public function shouldExecute()
    {
        if (!$this->active || $this->is_completed) {
            return false;
        }

        $now = now();
        
        // Si le rappel a une heure spécifique
        if ($this->time) {
            $currentTime = $now->format('H:i');
            $reminderTime = $this->time->format('H:i');

            // Vérifier si l'heure actuelle correspond à l'heure du rappel
            if ($currentTime !== $reminderTime) {
                return false;
            }
        }

        // Vérifier la fréquence
        if ($this->frequency) {
            switch ($this->frequency) {
                case 'daily':
                    return true;
                
                case 'weekly':
                    $currentDayOfWeek = $now->dayOfWeek;
                    return in_array($currentDayOfWeek, $this->days_of_week ?? []);
                
                case 'custom':
                    if (!$this->last_executed) {
                        return true;
                    }
                    
                    $daysSinceLastExecution = $now->diffInDays($this->last_executed);
                    return $daysSinceLastExecution >= $this->interval_days;
            }
        } else {
            // Comportement par défaut pour les rappels sans fréquence
            // Vérifier si la date d'échéance est aujourd'hui
            return $this->due_date && $now->isSameDay($this->due_date);
        }

        return false;
    }
}