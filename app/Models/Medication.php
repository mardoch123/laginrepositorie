<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medication extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'dosage',
        'frequency',
        'start_date',
        'end_date',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function treatments()
    {
        return $this->hasMany(Treatment::class);
    }

    public function getFrequencyLabelAttribute()
    {
        return [
            'daily' => 'Quotidien',
            'weekly' => 'Hebdomadaire',
            'monthly' => 'Mensuel',
        ][$this->frequency] ?? $this->frequency;
    }
}