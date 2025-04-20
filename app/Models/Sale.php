<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'rabbit_id',
        'breeding_id',
        'sale_type',
        'quantity',
        'weight_kg',
        'price_per_kg',
        'total_price',
        'sale_date',
        'customer_name',
        'customer_contact',
        'notes',
    ];

    protected $casts = [
        'sale_date' => 'date',
        'weight_kg' => 'decimal:2',
        'price_per_kg' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    // Relation avec le lapin vendu (si vente individuelle)
    public function rabbit()
    {
        return $this->belongsTo(Rabbit::class);
    }

    // Relation avec la portée vendue (si vente de portée)
    public function breeding()
    {
        return $this->belongsTo(Breeding::class);
    }

    // Calculer le prix total
    public function calculateTotalPrice()
    {
        return $this->weight_kg * $this->price_per_kg;
    }
}