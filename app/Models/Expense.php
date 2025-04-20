<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'amount',
        'category',
        'description',
        'supplier',
        'invoice_number'
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'float'
    ];
}