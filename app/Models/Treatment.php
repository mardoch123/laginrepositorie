<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Treatment extends Model
{
    use HasFactory;

    protected $fillable = [
        'rabbit_id',
        'medication_id',
        'scheduled_at',
        'administered_at',
        'completed_at',
        'status',
        'notes',
        'administered_by',
        'breeding_id',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'administered_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Les statuts valides pour un traitement.
     *
     * @var array
     */
    public static $statuses = [
        'pending',
        'completed',
        'cancelled',
        'skipped'
    ];

    public function rabbit()
    {
        return $this->belongsTo(Rabbit::class);
    }

    public function medication()
    {
        return $this->belongsTo(Medication::class);
    }

    public function getStatusLabelAttribute()
    {
        return [
            'pending' => 'À faire',
            'done' => 'Effectué',
            'skipped' => 'Ignoré',
        ][$this->status] ?? $this->status;
    }
}