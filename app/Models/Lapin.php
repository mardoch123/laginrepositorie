<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Lapin extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'identification_number',
        'gender',
        'birthdate',
        'breed',
        'cage',
        'status',
        'notes',
        'color',
        'weight',
    ];

    protected $casts = [
        'birthdate' => 'date',
    ];

    public function getAgeAttribute()
    {
        if (!$this->birthdate) {
            return null;
        }

        return $this->birthdate->diffForHumans(null, true);
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'alive' => 'success',
            'dead' => 'danger',
            'sold' => 'warning',
            'given' => 'info',
            default => 'secondary',
        };
    }
}