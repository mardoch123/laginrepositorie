<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diagnostic extends Model
{
    use HasFactory;

    protected $fillable = [
        'rabbit_id',
        'symptoms',
        'observed_date',
        'additional_notes',
        'temperature',
        'weight',
        'appetite_level',
        'activity_level',
        'ai_diagnosis',
        'veterinarian_notes',
        'treatment_plan',
        'follow_up_date',
    ];

    protected $casts = [
        'observed_date' => 'date',
        'follow_up_date' => 'date',
        'temperature' => 'float',
        'weight' => 'float',
    ];

    public function rabbit()
    {
        return $this->belongsTo(Rabbit::class);
    }
    
    public function healthRecords()
    {
        return $this->hasMany(HealthRecord::class);
    }
}