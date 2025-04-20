<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'food_id',
        'day_of_week', // 0 (dimanche) à 6 (samedi)
        'scheduled_date',
        'quantity',
        'unit',
        'is_completed',
        'completed_at',
        'notes'
    ];

    protected $casts = [
        'scheduled_date' => 'datetime',
        'completed_at' => 'datetime',
        'is_completed' => 'boolean',
    ];

    protected $table = 'food_schedules';

    public function food()
    {
        return $this->belongsTo(Food::class);
    }
}