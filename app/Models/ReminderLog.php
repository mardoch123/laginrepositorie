<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReminderLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'reminder_id',
        'executed_at',
        'success',
        'notes'
    ];

    protected $casts = [
        'executed_at' => 'datetime',
        'success' => 'boolean',
    ];

    public function reminder()
    {
        return $this->belongsTo(Reminder::class);
    }
}