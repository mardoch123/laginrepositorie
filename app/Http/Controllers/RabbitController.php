<?php

namespace App\Http\Controllers;

use App\Models\Rabbit;
use App\Models\Cage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Helpers\RabbitNameGenerator;

class RabbitController extends Controller
{
    /**
     * Affiche la liste des lapins.
     */
    public function index(Request $request)
    {
        $query = Rabbit::query()->with('cage');
        
        // Filtres
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('gender') && $request->gender) {
            $query->where('gender', $request->gender);
        }
        
        if ($request->has('cage_id') && $request->cage_id) {
            $query->where('cage_id', $request->cage_id);
        }
        
        $rabbits = $query->latest()->paginate(10);
        $cages = Cage::all();
        
        return view('rabbits.index', compact('rabbits', 'cages'));
    }

    /**
     * Affiche le formulaire de création d'un lapin.
     */
    public function create()
    {
        $cages = Cage::where('is_active', true)->get();
        return view('rabbits.create', compact('cages'));
    }

    /**
     * Enregistre un nouveau lapin dans la base de données.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'identification_number' => 'required|string|max:255|unique:rabbits',
            'gender' => ['required', Rule::in(['male', 'female'])],
            'birth_date' => 'required|date|before_or_equal:today',
            'breed' => 'required|string|max:255',
            'color' => 'nullable|string|max:255',
            'cage_id' => 'nullable|exists:cages,id',
            'status' => ['required', Rule::in(['alive', 'dead', 'sold', 'given'])],
            'is_active' => 'boolean',
            'notes' => 'nullable|string',
        ]);
        
        Rabbit::create($validated);
        
        return redirect()->route('rabbits.index')
            ->with('success', 'Lapin ajouté avec succès.');
    }

    /**
     * Affiche les détails d'un lapin spécifique.
     */
    public function show(Rabbit $rabbit)
    {
        return view('rabbits.show', compact('rabbit'));
    }

    /**
     * Affiche le formulaire d'édition d'un lapin.
     */
    public function edit(Rabbit $rabbit)
    {
        $cages = Cage::where('is_active', true)->get();
        return view('rabbits.edit', compact('rabbit', 'cages'));
    }

    /**
     * Met à jour un lapin dans la base de données.
     */
    public function update(Request $request, Rabbit $rabbit)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'identification_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('rabbits')->ignore($rabbit->id),
            ],
            'gender' => ['required', Rule::in(['male', 'female'])],
            'birth_date' => 'required|date|before_or_equal:today',
            'breed' => 'required|string|max:255',
            'color' => 'nullable|string|max:255',
            'cage_id' => 'nullable|exists:cages,id',
            'status' => ['required', Rule::in(['alive', 'dead', 'sold', 'given'])],
            'is_active' => 'boolean',
            'notes' => 'nullable|string',
        ]);
        
        $rabbit->update($validated);
        
        return redirect()->route('rabbits.index')
            ->with('success', 'Lapin mis à jour avec succès.');
    }

    /**
     * Supprime un lapin de la base de données.
     */
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rabbit $rabbit)
    {
        try {
            // Vérifier si le lapin peut être supprimé
            $canDelete = true;
            $dependencies = [];
            
            // Vérifier les reproductions (si cette relation existe)
            if (method_exists($rabbit, 'breedings') && $rabbit->breedings()->count() > 0) {
                $canDelete = false;
                $dependencies[] = 'reproductions';
            }
            
            // Vérifier les dossiers médicaux (si cette relation existe)
            if (method_exists($rabbit, 'healthRecords') && $rabbit->healthRecords()->count() > 0) {
                $canDelete = false;
                $dependencies[] = 'dossiers médicaux';
            }
            
            // Vérifier les ventes (si cette relation existe)
            if (method_exists($rabbit, 'sales') && $rabbit->sales()->count() > 0) {
                $canDelete = false;
                $dependencies[] = 'ventes';
            }
            
            // Ajouter d'autres vérifications selon votre modèle de données
            
            if (!$canDelete) {
                $dependenciesText = implode(', ', $dependencies);
                return redirect()->route('rabbits.index')
                    ->with('error', "Impossible de supprimer ce lapin car il est associé à des {$dependenciesText}. Veuillez d'abord supprimer ces enregistrements.");
            }
            
            // Si aucune dépendance n'est trouvée, supprimer le lapin
            $rabbit->delete();
            
            return redirect()->route('rabbits.index')
                ->with('success', 'Lapin supprimé avec succès.');
        } catch (\Exception $e) {
            return redirect()->route('rabbits.index')
                ->with('error', 'Une erreur est survenue lors de la suppression du lapin : ' . $e->getMessage());
        }
    }
    
    /**
     * Génère des suggestions de noms pour les lapins.
     */
    public function nameSuggestions(Request $request)
    {
        $gender = $request->input('gender', 'male');
        $color = $request->input('color');
        $breed = $request->input('breed');
        
        $suggestions = RabbitNameGenerator::generateSuggestions($gender, $color, $breed);
        
        return response()->json([
            'success' => true,
            'suggestions' => $suggestions
        ]);
    }

    /**
     * Enregistre un nouveau poids pour un lapin ou une portée.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function recordWeight(Request $request)
    {
        $validated = $request->validate([
            'record_type' => 'required|in:rabbit,breeding',
            'record_id' => 'required|integer',
            'weight' => 'required|numeric|min:1',
            'weight_date' => 'required|date',
        ]);
        
        try {
            if ($request->record_type === 'rabbit') {
                // Enregistrer le poids pour un lapin individuel
                $rabbit = Rabbit::findOrFail($request->record_id);
                
                // Enregistrer le poids dans l'historique
                $weightRecord = new \App\Models\WeightRecord();
                $weightRecord->rabbit_id = $rabbit->id;
                $weightRecord->weight = $request->weight;
                $weightRecord->recorded_at = $request->weight_date;
                $weightRecord->save();
                
                // Mettre à jour le poids actuel du lapin
                $rabbit->current_weight = $request->weight;
                $rabbit->save();
                
                return redirect()->back()->with('success', 'Le poids de ' . $rabbit->name . ' a été enregistré avec succès.');
            } else {
                // Enregistrer le poids pour une portée
                $breeding = \App\Models\Breeding::findOrFail($request->record_id);
                
                // Calculer le poids moyen par lapereau
                $averageWeight = round($request->weight / $breeding->number_of_kits);
                
                // Enregistrer le poids dans l'historique de la portée
                $breedingWeightRecord = new \App\Models\BreedingWeightRecord();
                $breedingWeightRecord->breeding_id = $breeding->id;
                $breedingWeightRecord->total_weight = $request->weight;
                $breedingWeightRecord->average_weight = $averageWeight;
                $breedingWeightRecord->recorded_at = $request->weight_date;
                $breedingWeightRecord->save();
                
                // Mettre à jour le poids actuel de la portée
                $breeding->current_total_weight = $request->weight;
                $breeding->current_average_weight = $averageWeight;
                $breeding->save();
                
                return redirect()->back()->with('success', 'Le poids de la portée de ' . $breeding->mother->name . ' a été enregistré avec succès.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue: ' . $e->getMessage());
        }
    }

}