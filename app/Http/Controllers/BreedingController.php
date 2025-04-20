<?php

namespace App\Http\Controllers;

use App\Models\Breeding;
use App\Models\Rabbit;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Events\BirthReminder;

class BreedingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $breedings = Breeding::with(['mother', 'father'])->latest()->paginate(10);
        return view('breedings.index', compact('breedings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Récupérer les femelles de plus de 5 mois
        $females = Rabbit::where('gender', 'female')
            ->where('status', 'alive')
            ->where('birth_date', '<=', Carbon::now()->subMonths(5))
            ->get()
            ->map(function ($rabbit) {
                // Ajouter l'âge en mois pour l'affichage
                $rabbit->age_months = $rabbit->birth_date->diffInMonths(Carbon::now());
                return $rabbit;
            });
        
        // Récupérer les mâles de plus de 6 mois
        $males = Rabbit::where('gender', 'male')
            ->where('status', 'alive')
            ->where('birth_date', '<=', Carbon::now()->subMonths(6))
            ->get()
            ->map(function ($rabbit) {
                // Ajouter l'âge en mois pour l'affichage
                $rabbit->age_months = $rabbit->birth_date->diffInMonths(Carbon::now());
                return $rabbit;
            });
        
        return view('breedings.create', compact('females', 'males'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'mother_id' => 'required|exists:rabbits,id',
            'father_id' => 'required|exists:rabbits,id',
            'mating_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);
        
        $breeding = new Breeding($validated);
        $breeding->expected_birth_date = $breeding->calculateExpectedBirthDate();
        $breeding->save();
        
        return redirect()->route('breedings.index')
                        ->with('success', 'Nouvelle portée enregistrée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Breeding $breeding)
    {
        return view('breedings.show', compact('breeding'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Breeding $breeding)
    {
        $fourMonthsAgo = Carbon::now()->subMonths(4);
        
        $females = Rabbit::where('gender', 'female')
                        ->whereNotNull('date_of_birth')
                        ->where('date_of_birth', '<=', $fourMonthsAgo)
                        ->get();
                        
        $males = Rabbit::where('gender', 'male')
                      ->whereNotNull('date_of_birth')
                      ->where('date_of_birth', '<=', $fourMonthsAgo)
                      ->get();
                      
        return view('breedings.edit', compact('breeding', 'females', 'males'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Breeding $breeding)
    {
        $validated = $request->validate([
            'mother_id' => 'required|exists:rabbits,id',
            'father_id' => 'required|exists:rabbits,id',
            'mating_date' => 'required|date',
            'actual_birth_date' => 'nullable|date',
            'weaning_date' => 'nullable|date',
            'fattening_start_date' => 'nullable|date',
            'expected_fattening_end_date' => 'nullable|date',
            'number_of_kits' => 'nullable|integer|min:0',
            'number_of_males' => 'nullable|integer|min:0',
            'number_of_females' => 'nullable|integer|min:0',
            'weaning_confirmed' => 'nullable|boolean',
            'fattening_confirmed' => 'nullable|boolean',
            'notes' => 'nullable|string',
        ]);
    
        // Vérifier que le nombre de mâles et femelles correspond au total
        if ($request->filled('number_of_kits') && $request->filled('number_of_males') && $request->filled('number_of_females')) {
            $totalByGender = $request->number_of_males + $request->number_of_females;
            
            if ($totalByGender != $request->number_of_kits) {
                return back()
                    ->withInput()
                    ->withErrors(['number_of_kits' => 'Le nombre total de petits doit être égal à la somme des mâles et femelles.']);
            }
        }
    
        // Recalculer la date prévue si la date d'accouplement a changé
        if ($breeding->mating_date->format('Y-m-d') != $validated['mating_date']) {
            $matingDate = Carbon::parse($validated['mating_date']);
            $expectedBirthDate = $matingDate->copy()->addDays(31);
            $validated['expected_birth_date'] = $expectedBirthDate;
        }
    
        // Mettre à jour les dates de cycle de vie
        if (!empty($validated['actual_birth_date']) && (!$breeding->actual_birth_date || $breeding->actual_birth_date->format('Y-m-d') != $validated['actual_birth_date'])) {
            $birthDate = Carbon::parse($validated['actual_birth_date']);
            $validated['weaning_date'] = $birthDate->copy()->addDays(30);
        }
    
        if (isset($validated['weaning_confirmed']) && $validated['weaning_confirmed'] && !$breeding->weaning_confirmed) {
            if (empty($validated['fattening_start_date'])) {
                $validated['fattening_start_date'] = $validated['weaning_date'] ?? $breeding->weaning_date;
            }
            
            $fatteningStartDate = Carbon::parse($validated['fattening_start_date']);
            $validated['expected_fattening_end_date'] = $fatteningStartDate->copy()->addDays(75);
        }
    
        $breeding->update($validated);
    
        return redirect()->route('breedings.index')
            ->with('success', 'Portée mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Breeding $breeding)
    {
        $breeding->delete();
        
        return redirect()->route('breedings.index')
                        ->with('success', 'Portée supprimée avec succès.');
    }
    
    /**
     * Display the breeding calendar.
     */
    public function calendar()
    {
        $breedings = Breeding::with(['mother', 'father'])->get();
        
        $events = [];
        
        foreach ($breedings as $breeding) {
            // Mating event (blue)
            $events[] = [
                'title' => 'Accouplement: ' . $breeding->mother->name . ' & ' . $breeding->father->name,
                'start' => $breeding->mating_date->format('Y-m-d'),
                'backgroundColor' => '#3490dc', // blue
                'borderColor' => '#3490dc',
                'url' => route('breedings.show', $breeding),
                'description' => 'Accouplement entre ' . $breeding->mother->name . ' et ' . $breeding->father->name
            ];
            
            // Expected birth event (orange)
            if ($breeding->expected_birth_date) {
                $events[] = [
                    'title' => 'Naissance prévue: ' . $breeding->mother->name,
                    'start' => $breeding->expected_birth_date->format('Y-m-d'),
                    'backgroundColor' => '#f6993f', // orange
                    'borderColor' => '#f6993f',
                    'url' => route('breedings.show', $breeding),
                    'description' => 'Naissance prévue pour la portée de ' . $breeding->mother->name
                ];
            }
            
            // Actual birth event (green)
            if ($breeding->actual_birth_date) {
                $events[] = [
                    'title' => 'Naissance: ' . $breeding->mother->name . ' (' . $breeding->number_of_kits . ' petits)',
                    'start' => $breeding->actual_birth_date->format('Y-m-d'),
                    'backgroundColor' => '#38c172', // green
                    'borderColor' => '#38c172',
                    'url' => route('breedings.show', $breeding),
                    'description' => 'Naissance de ' . $breeding->number_of_kits . ' petits pour ' . $breeding->mother->name
                ];
            }
        }
        
        return view('breedings.calendar', compact('events'));
    }
}