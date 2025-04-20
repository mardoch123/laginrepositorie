<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Breeding;
use App\Models\Cage;
use App\Models\Rabbit;
use App\Models\Treatment;
use App\Models\FoodSchedule;
use Carbon\Carbon;

class KitController extends Controller
{
    public function index()
    {
        // Récupérer toutes les portées avec des lapereaux non sevrés
        $breedings = Breeding::with(['mother', 'father'])
            ->whereNotNull('actual_birth_date')
            ->where(function($query) {
                $query->where('weaning_confirmed', false)
                      ->orWhereNull('weaning_confirmed');
            })
            ->orderBy('actual_birth_date', 'desc')
            ->get();
            
        // Calculer l'âge en jours pour chaque portée
        $breedings->each(function($breeding) {
            $breeding->age_days = $breeding->actual_birth_date->diffInDays(Carbon::now());
        });
        
        return view('kits.index', compact('breedings'));
    }
    
 /**
 * Affiche la page d'engraissement des lapereaux.
 *
 * @return \Illuminate\View\View
 */
public function fattening()
{
    // Récupérer les lapins individuels avec le statut 'fattening'
    $fatteningRabbits = Rabbit::where('status', 'fattening')
                            ->orWhere(function($query) {
                                $query->where('category', 'kit')
                                      ->where('status', 'active')
                                      ->whereNotNull('weaning_date');
                            })
                            ->get();
    
    // Récupérer les portées dont le sevrage est confirmé
    $weanedBreedings = Breeding::where('weaning_confirmed', true)
                              ->with(['mother', 'father'])
                              ->get();
    
    // Récupérer les cages disponibles pour l'engraissement
    $cages = Cage::where('status', 'available')
        ->where(function($query) {
            $query->where('type', 'fattening')
                  ->orWhere('type', 'multi-purpose');
        })
        ->get();
    
    // Journaliser les informations pour le débogage
    foreach ($fatteningRabbits as $rabbit) {
        \Log::info("Lapin en engraissement: ID={$rabbit->id}, Nom={$rabbit->name}, Statut={$rabbit->status}, Catégorie={$rabbit->category}");
    }
    
    foreach ($weanedBreedings as $breeding) {
        \Log::info("Portée en engraissement: ID={$breeding->id}, Mère={$breeding->mother->name}, Nombre de lapereaux={$breeding->number_of_kits}");
    }
    
    return view('rabbits.fattening', compact('fatteningRabbits', 'weanedBreedings', 'cages'));
}
/**
 * Démarre l'engraissement pour les lapins sélectionnés.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\Response
 */
public function startFattening(Request $request)
{
    $validated = $request->validate([
        'rabbit_ids' => 'required|array',
        'rabbit_ids.*' => 'exists:rabbits,id',
        'cage_id' => 'required|exists:cages,id',
        'notes' => 'nullable|string',
    ]);
    
    try {
        $cage = Cage::findOrFail($request->cage_id);
        $rabbitsCount = count($request->rabbit_ids);
        
        // Vérifier si la cage a assez de place
        if ($cage->capacity && $cage->rabbits()->count() + $rabbitsCount > $cage->capacity) {
            return redirect()->back()->with('error', 'La cage sélectionnée n\'a pas assez de place pour tous les lapins sélectionnés.');
        }
        
        // Mettre à jour le statut des lapins et les assigner à la cage
        foreach ($request->rabbit_ids as $rabbitId) {
            $rabbit = Rabbit::findOrFail($rabbitId);
            $rabbit->status = 'fattening';
            $rabbit->cage_id = $cage->id;
            $rabbit->notes = $rabbit->notes . "\n" . date('Y-m-d') . " - Début de l'engraissement. " . $request->notes;
            $rabbit->save();
        }
        
        return redirect()->route('kits.fattening')->with('success', $rabbitsCount . ' lapins ont été mis en engraissement avec succès.');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Une erreur est survenue: ' . $e->getMessage());
    }
}

public function assignCage(Request $request)
{
    $validated = $request->validate([
        'cage_id' => 'required|exists:cages,id',
        'breeding_ids' => 'required|string',
    ]);
    
    $breedingIds = explode(',', $validated['breeding_ids']);
    $cageId = $validated['cage_id'];
    
    // Assign the selected breedings to the selected cage
    // This depends on your data model, but might look something like:
    foreach ($breedingIds as $breedingId) {
        $breeding = Breeding::find($breedingId);
        if ($breeding) {
            $breeding->cage_id = $cageId;
            $breeding->save();
        }
    }
    
    return redirect()->route('kits.fattening')
        ->with('success', 'Lapereaux assignés à la cage avec succès.');
}
}