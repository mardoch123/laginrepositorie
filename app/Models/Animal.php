<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Animal extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'identification_number',
        'gender',
        'birth_date',
        'breed',
        'color',
        'status',
        'notes',
    ];

    protected $dates = [
        'birth_date',
    ];

    public function getAgeMonthsAttribute()
    {
        return $this->birth_date->diffInMonths(Carbon::now());
    }

    public function motherBreedings()
    {
        return $this->hasMany(Breeding::class, 'mother_id');
    }

    public function fatherBreedings()
    {
        return $this->hasMany(Breeding::class, 'father_id');
    }
}