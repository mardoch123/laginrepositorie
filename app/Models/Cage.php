<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'location',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relation avec les lapins
    public function rabbits()
    {
        return $this->hasMany(Rabbit::class);
    }
}