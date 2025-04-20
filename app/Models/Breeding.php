<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Breeding extends Model
{
    use HasFactory;

    protected $fillable = [
        'mother_id',
        'father_id',
        'mating_date',
        'expected_birth_date',
        'actual_birth_date',
        'weaning_date',
        'fattening_start_date',
        'expected_fattening_end_date',
        'number_of_kits',
        'number_of_males',
        'number_of_females',
        'weaning_confirmed',
        'fattening_confirmed',
        'notes',
        'status',
        'current_total_weight',
        'current_average_weight',
    ];

    protected $casts = [
        'mating_date' => 'date',
        'expected_birth_date' => 'date',
        'actual_birth_date' => 'date',
        'weaning_date' => 'date',
        'fattening_start_date' => 'date',
        'expected_fattening_end_date' => 'date',
        'weaning_confirmed' => 'boolean',
        'fattening_confirmed' => 'boolean',
    ];

    // Relation avec les lapereaux
    public function kits()
    {
        return $this->hasMany(Rabbit::class, 'breeding_id');
    }
    
    public function mother()
    {
        return $this->belongsTo(Rabbit::class, 'mother_id');
    }
    
    public function father()
    {
        return $this->belongsTo(Rabbit::class, 'father_id');
    }

    public function calculateExpectedBirthDate()
    {
        if ($this->mating_date) {
            // Pregnancy duration for rabbits is typically 28-35 days
            // Using 31 days as an average
            return Carbon::parse($this->mating_date)->addDays(31);
        }
        return null;
    }

    public function calculateWeaningDate()
    {
        if ($this->actual_birth_date) {
            // Weaning typically occurs 30 days after birth
            return Carbon::parse($this->actual_birth_date)->addDays(30);
        }
        return null;
    }

    public function calculateFatteningEndDate()
    {
        if ($this->fattening_start_date) {
            // Fattening period is typically 75 days
            return Carbon::parse($this->fattening_start_date)->addDays(75);
        } elseif ($this->weaning_date) {
            // If fattening start date is not set but weaning date is,
            // calculate from weaning date (weaning + 75 days)
            return Carbon::parse($this->weaning_date)->addDays(75);
        }
        return null;
    }

    public function getStatusAttribute($value)
    {
        if (!$value) {
            $today = Carbon::today();
            
            if (!$this->mating_date) {
                return 'pending';
            }
            
            if ($this->actual_birth_date) {
                if (!$this->weaning_date) {
                    // Si la date de naissance est définie mais pas la date de sevrage
                    $this->weaning_date = $this->calculateWeaningDate();
                }
                
                if ($this->weaning_date) {
                    // Si la date de sevrage approche (dans les 3 jours)
                    if (!$this->weaning_confirmed && $today->diffInDays($this->weaning_date, false) <= 3 && $today->diffInDays($this->weaning_date, false) >= 0) {
                        return 'weaning_soon';
                    }
                    
                    // Si la date de sevrage est passée mais pas confirmée
                    if (!$this->weaning_confirmed && $today->gt($this->weaning_date)) {
                        return 'weaning_overdue';
                    }
                    
                    // Si le sevrage est confirmé mais pas l'engraissement
                    if ($this->weaning_confirmed && !$this->fattening_confirmed) {
                        if (!$this->fattening_start_date) {
                            $this->fattening_start_date = $this->weaning_date;
                        }
                        
                        if (!$this->expected_fattening_end_date) {
                            $this->expected_fattening_end_date = $this->calculateFatteningEndDate();
                        }
                        
                        // Si la fin d'engraissement approche
                        if ($today->diffInDays($this->expected_fattening_end_date, false) <= 3 && $today->diffInDays($this->expected_fattening_end_date, false) >= 0) {
                            return 'fattening_ending_soon';
                        }
                        
                        // Si la date de fin d'engraissement est passée
                        if ($today->gt($this->expected_fattening_end_date)) {
                            return 'fattening_overdue';
                        }
                        
                        return 'fattening';
                    }
                    
                    if ($this->fattening_confirmed) {
                        return 'completed';
                    }
                    
                    return 'weaning';
                }
                
                return 'born';
            }
            
            if ($this->expected_birth_date && $today->gt($this->expected_birth_date)) {
                return 'birth_overdue';
            }
            
            if ($this->expected_birth_date && $today->diffInDays($this->expected_birth_date, false) <= 3 && $today->diffInDays($this->expected_birth_date, false) >= 0) {
                return 'birth_imminent';
            }
            
            return 'pregnant';
        }
        
        return $value;
    }

    public function getStatusLabelAttribute()
    {
        $statuses = [
            'pending' => 'En attente',
            'pregnant' => 'Gestante',
            'birth_imminent' => 'Naissance imminente',
            'birth_overdue' => 'Naissance en retard',
            'born' => 'Nés',
            'weaning_soon' => 'Sevrage imminent',
            'weaning' => 'En sevrage',
            'weaning_overdue' => 'Sevrage en retard',
            'fattening' => 'En engraissement',
            'fattening_ending_soon' => 'Fin d\'engraissement imminente',
            'fattening_overdue' => 'Engraissement terminé',
            'completed' => 'Cycle terminé',
        ];
        
        return $statuses[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'gray',
            'pregnant' => 'blue',
            'birth_imminent' => 'yellow',
            'birth_overdue' => 'red',
            'born' => 'green',
            'weaning_soon' => 'yellow',
            'weaning' => 'blue',
            'weaning_overdue' => 'red',
            'fattening' => 'purple',
            'fattening_ending_soon' => 'yellow',
            'fattening_overdue' => 'red',
            'completed' => 'green',
        ];
        
        return $colors[$this->status] ?? 'gray';
    }

    public function getNextActionAttribute()
    {
        switch ($this->status) {
            case 'birth_overdue':
                return 'Vérifier si la naissance a eu lieu et mettre à jour les informations';
            case 'birth_imminent':
                return 'Préparer la cage pour la naissance';
            case 'born':
                return 'Surveiller les lapereaux et préparer le sevrage';
            case 'weaning_soon':
                return 'Préparer le sevrage des lapereaux';
            case 'weaning_overdue':
                return 'Confirmer le sevrage des lapereaux';
            case 'fattening_ending_soon':
                return 'Préparer la fin de l\'engraissement';
            case 'fattening_overdue':
                return 'Confirmer la fin de l\'engraissement';
            default:
                return null;
        }
    }

    /**
     * Relation avec les ventes
     */
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * Vérifie si la portée a été vendue
     */
    public function isSold()
    {
        return $this->sales()->exists();
    }

    /**
     * Obtenir les portées associées à cet élevage.
     */
    public function litters()
    {
        return $this->hasMany(Litter::class);
    }

    protected static function booted()
    {
        static::saving(function ($breeding) {
            // Si la date de naissance réelle est définie et que la date de sevrage ne l'est pas
            if ($breeding->actual_birth_date && !$breeding->weaning_date) {
                $breeding->weaning_date = $breeding->calculateWeaningDate();
            }
            
            // Si la date de sevrage est définie et que la date de début d'engraissement ne l'est pas
            if ($breeding->weaning_confirmed && !$breeding->fattening_start_date) {
                $breeding->fattening_start_date = $breeding->weaning_date;
            }
            
            // Si la date de début d'engraissement est définie et que la date de fin d'engraissement ne l'est pas
            if ($breeding->fattening_start_date && !$breeding->expected_fattening_end_date) {
                $breeding->expected_fattening_end_date = $breeding->calculateFatteningEndDate();
            }
        });
    }
}