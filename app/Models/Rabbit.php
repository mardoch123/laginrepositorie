<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Rabbit extends Model
{
    use HasFactory;

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($rabbit) {
             // Supprimer les enregistrements liés
             if (method_exists($rabbit, 'breedings')) {
                $rabbit->breedings()->delete();
            }
            
            // Supprimer les reproductions où ce lapin est impliqué
            if (method_exists($rabbit, 'maleBreedings')) {
                $rabbit->maleBreedings()->delete();
            }
            
            if (method_exists($rabbit, 'femaleBreedings')) {
                $rabbit->femaleBreedings()->delete();
            }
            
            // Supprimer les dossiers médicaux
            if (method_exists($rabbit, 'healthRecords')) {
                $rabbit->healthRecords()->delete();
            }
            
            // Supprimer les ventes
            if (method_exists($rabbit, 'sales')) {
                $rabbit->sales()->delete();
            }
            
            // Supprimer les poids
            if (method_exists($rabbit, 'weights')) {
                $rabbit->weights()->delete();
            }
            
            // Supprimer les vaccinations
            if (method_exists($rabbit, 'vaccinations')) {
                $rabbit->vaccinations()->delete();
            }
            
            // Supprimer les traitements
            if (method_exists($rabbit, 'treatments')) {
                $rabbit->treatments()->delete();
            }
            
        });
    }

    protected $fillable = [
        'name',
        'identification_number',
        'gender',
        'birth_date',
        'breed',
        'color',
        'notes',
        'is_active',
        'cage_id',
        'status',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'is_active' => 'boolean',
    ];

    // Relation avec la cage
    public function cage()
    {
        return $this->belongsTo(Cage::class);
    }

    // Relation avec les portées (en tant que mère)
    public function motherLitters()
    {
        return $this->hasMany(Litter::class, 'mother_id');
    }

    // Relation avec les portées (en tant que père)
    public function fatherLitters()
    {
        return $this->hasMany(Litter::class, 'father_id');
    }

    // Accesseur pour calculer l'âge
    public function getAgeAttribute()
    {
        if (!$this->birth_date) {
            return null;
        }

        return $this->birth_date->diffForHumans(null, true);
    }

    // Accesseur pour calculer l'âge en jours
    public function getAgeDaysAttribute()
    {
        if (!$this->birth_date) {
            return null;
        }

        return $this->birth_date->diffInDays(Carbon::now());
    }

    // Accesseur pour calculer l'âge en mois
    public function getAgeMonthsAttribute()
    {
        if (!$this->birth_date) {
            return null;
        }

        return $this->birth_date->diffInMonths(Carbon::now());
    }

    // Scope pour filtrer par statut
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Scope pour filtrer par sexe
    public function scopeGender($query, $gender)
    {
        return $query->where('gender', $gender);
    }

    // Scope pour filtrer par cage
    public function scopeCage($query, $cageId)
    {
        return $query->where('cage_id', $cageId);
    }
    
    // Ajouter cette méthode dans le modèle Rabbit
    
    /**
     * Relation avec les ventes
     */
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
    
    /**
     * Vérifie si le lapin a été vendu
     */
    public function isSold()
    {
        return $this->sales()->exists();
    }
    public function treatments()
    {
        return $this->hasMany(Treatment::class);
    }
    // Relation avec la portée à laquelle appartient ce lapin
    public function breeding()
    {
        return $this->belongsTo(Breeding::class, 'breeding_id');
    }
    
    // Relation avec les portées dont ce lapin est la mère
    public function motherBreedings()
    {
        return $this->hasMany(Breeding::class, 'mother_id');
    }
    
    // Relation avec les portées dont ce lapin est le père
    public function fatherBreedings()
    {
        return $this->hasMany(Breeding::class, 'father_id');
    }
    
    /**
     * Relation avec les enregistrements de mortalité
     */
    public function healthRecords()
    {
        return $this->hasMany(HealthRecord::class);
    }
    public function mortalities()
    {
        return $this->hasMany(Mortality::class);
    }

    /**
     * Relation avec la mère du lapin
     */
    public function mother()
    {
        return $this->belongsTo(Rabbit::class, 'mother_id');
    }

    /**
     * Relation avec le père du lapin
     */
    public function father()
    {
        return $this->belongsTo(Rabbit::class, 'father_id');
    }

    /**
     * Get the litter that the rabbit belongs to.
     */
    public function litter()
    {
        return $this->belongsTo(Litter::class);
    }
}