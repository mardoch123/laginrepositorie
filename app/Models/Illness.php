<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Illness extends Model
{
    use HasFactory;

    protected $fillable = [
        'rabbit_id',
        'type',
        'symptoms',
        'detection_date',
        'severity',
        'notes',
        'status',
        'cure_date',
        'recovery_date'
    ];

    protected $casts = [
        'detection_date' => 'date',
        'cure_date' => 'datetime',
        'symptoms' => 'array',
        'recovery_date' => 'date',
    ];

    /**
     * Relation avec le lapin
     */
    public function rabbit()
    {
        return $this->belongsTo(Rabbit::class);
    }

    /**
     * Relation avec les traitements
     */
    public function treatments()
    {
        return $this->hasMany(Treatment::class);
    }
    
    /**
     * Relation avec les enregistrements de mortalité
     */
    public function mortalities()
    {
        return $this->hasMany(Mortality::class);
    }
    
    /**
     * Vérifie si la maladie est active
     */
    public function isActive()
    {
        return $this->status === 'active';
    }
    
    /**
     * Vérifie si la maladie est guérie
     */
    public function isCured()
    {
        return $this->status === 'cured';
    }
    
    /**
     * Vérifie si la maladie a été fatale
     */
    public function isFatal()
    {
        return $this->status === 'fatal';
    }
    
    /**
     * Retourne la durée de la maladie
     */
    public function getDuration()
    {
        if ($this->isActive()) {
            return $this->detection_date->diffInDays(now()) . ' jours (en cours)';
        } elseif ($this->isCured() && $this->cure_date) {
            return $this->detection_date->diffInDays($this->cure_date) . ' jours';
        } else {
            return 'N/A';
        }
    }
}