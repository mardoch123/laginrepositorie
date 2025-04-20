<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeightRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'rabbit_id',
        'weight',
        'recorded_at',
        'notes'
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
        'weight' => 'float'
    ];

    public function rabbit()
    {
        return $this->belongsTo(Rabbit::class);
    }
}