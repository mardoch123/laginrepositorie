<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mortality extends Model
{
    use HasFactory;

    protected $fillable = [
        'rabbit_id',
        'breeding_id',
        'illness_id',
        'death_date',
        'death_cause',
        'kit_count',
        'kit_sex',
        'notes',
    ];

    protected $casts = [
        'death_date' => 'date',
    ];

    /**
     * Relation avec le lapin
     */
    public function rabbit()
    {
        return $this->belongsTo(Rabbit::class);
    }

    /**
     * Relation avec la portée
     */
    public function breeding()
    {
        return $this->belongsTo(Breeding::class);
    }

    /**
     * Relation avec la maladie
     */
    public function illness()
    {
        return $this->belongsTo(Illness::class);
    }

    /**
     * Vérifie si la mortalité concerne un lapin individuel
     */
    public function isIndividual()
    {
        return !is_null($this->rabbit_id);
    }

    /**
     * Vérifie si la mortalité concerne une portée
     */
    public function isLitter()
    {
        return !is_null($this->breeding_id);
    }

    /**
     * Vérifie si la mortalité est due à une maladie
     */
    public function isDueToIllness()
    {
        return $this->death_cause === 'illness' && !is_null($this->illness_id);
    }
}