<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Litter extends Model
{
    use HasFactory;

    protected $fillable = [
        'mother_id',
        'father_id',
        'breeding_date',
        'expected_birth_date',
        'actual_birth_date',
        'expected_size',
        'born_alive',
        'breeding_id',
        'born_dead',
        'current_count',
        'weaning_date',
        'notes',
        'status',
    ];

    protected $casts = [
        'breeding_date' => 'date',
        'expected_birth_date' => 'date',
        'actual_birth_date' => 'date',
        'weaning_date' => 'date',
    ];

    public function mother(): BelongsTo
    {
        return $this->belongsTo(Rabbit::class, 'mother_id');
    }

    public function father(): BelongsTo
    {
        return $this->belongsTo(Rabbit::class, 'father_id');
    }

    public function reminders(): HasMany
    {
        return $this->hasMany(Reminder::class);
    }

    public function breeding()
    {
        return $this->belongsTo(Breeding::class);
    }
}