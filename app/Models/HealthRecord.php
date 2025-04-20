<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'rabbit_id',
        'condition',
        'treatment',
        'outcome',
        'notes',
        'date',
        'veterinarian',
        'cost',
    ];

    protected $casts = [
        'date' => 'date',
        'cost' => 'decimal:2',
    ];

    /**
     * Relation avec le lapin
     */
    public function rabbit()
    {
        return $this->belongsTo(Rabbit::class);
    }
}