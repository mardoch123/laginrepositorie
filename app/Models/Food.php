<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    use HasFactory;

    // Spécifier explicitement le nom de la table
    protected $table = 'foods';

    protected $fillable = [
        'name',
        'description',
        'frequency', // daily, alternate_days, weekly, etc.
        'quantity_per_rabbit',
        'unit', // g, kg, etc.
        'is_active',
        'notes',
    ];

    public function schedules()
    {
        return $this->hasMany(FoodSchedule::class);
    }
}