<?php

namespace App\Http\Controllers;

// Ajouter l'import du modèle Expense
use App\Models\Treatment;
use App\Models\Rabbit;
use App\Models\Medication;
use App\Models\Breeding;
use App\Models\Expense;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Litter;
use Illuminate\Support\Facades\Log;

class TreatmentController extends Controller
{
    /**
     * Affiche la liste des traitements.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Treatment::with(['rabbit', 'medication']);
        
        // Filtre par statut
        if ($request->filled('status')) {
            if ($request->status === 'overdue') {
                $query->where('status', 'pending')
                      ->where('scheduled_at', '<', now()->startOfDay());
            } elseif ($request->status === 'today') {
                $query->where('status', 'pending')
                      ->whereDate('scheduled_at', now()->toDateString());
            } else {
                $query->where('status', $request->status);
            }
        }
        
        // Filtre par lapin
        if ($request->filled('rabbit_id')) {
            $query->where('rabbit_id', $request->rabbit_id);
        }
        
        // Filtre par médicament
        if ($request->filled('medication_id')) {
            $query->where('medication_id', $request->medication_id);
        }
        
        // Filtre par période
        if ($request->filled('date_range')) {
            switch ($request->date_range) {
                case 'today':
                    $query->whereDate('scheduled_at', now()->toDateString());
                    break;
                case 'tomorrow':
                    $query->whereDate('scheduled_at', now()->addDay()->toDateString());
                    break;
                case 'this_week':
                    $query->whereBetween('scheduled_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'next_week':
                    $query->whereBetween('scheduled_at', [now()->addWeek()->startOfWeek(), now()->addWeek()->endOfWeek()]);
                    break;
                case 'this_month':
                    $query->whereMonth('scheduled_at', now()->month)
                          ->whereYear('scheduled_at', now()->year);
                    break;
            }
        }
        
        // Statistiques pour le tableau de bord
        $stats = [
            'pending' => Treatment::where('status', 'pending')->count(),
            'completed' => Treatment::where('status', 'completed')->count(),
            'today' => Treatment::where('status', 'pending')
                                ->whereDate('scheduled_at', now()->toDateString())
                                ->count(),
            'overdue' => Treatment::where('status', 'pending')
                                 ->where('scheduled_at', '<', now()->startOfDay())
                                 ->count(),
        ];
        
        // Récupérer les lapins et médicaments pour les filtres
        $rabbits = Rabbit::all();
        $medications = Medication::all();
        
        $treatments = $query->orderBy('scheduled_at')->paginate(15);
                
        return view('treatments.index', compact('treatments', 'stats', 'rabbits', 'medications'));
    }

    /**
     * Affiche le formulaire de création d'un traitement.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $rabbits = Rabbit::all();
        $medications = Medication::all();
        
        // Modification de la requête pour récupérer les portées
        // Assurez-vous que le statut correspond à ceux définis dans votre modèle Breeding
        $litters = Breeding::with(['mother', 'kits'])
            ->whereNotIn('status', ['completed'])
            ->orWhereNull('status')  // Pour inclure les portées sans statut défini
            ->get();
        
        // Débogage pour vérifier les portées récupérées
        // dd($litters);  // Décommentez cette ligne pour voir les portées récupérées
        
        return view('treatments.create', compact('rabbits', 'medications', 'litters'));
    }

    /**
 * Affiche les traitements à venir dans les prochaines heures
 */
public function upcoming()
{
    $now = Carbon::now();
    $threeHoursLater = $now->copy()->addHours(3);
    
    $upcomingTreatments = Treatment::with(['rabbit', 'medication'])
        ->where('status', 'pending')
        ->whereBetween('scheduled_at', [$now, $threeHoursLater])
        ->orderBy('scheduled_at')
        ->get();
    
    $pendingTreatments = Treatment::where('status', 'pending')
        ->where('scheduled_at', '<', $now)
        ->count();
    
    return view('treatments.upcoming', compact('upcomingTreatments', 'pendingTreatments'));
}
    
    /**
 * Store a newly created resource in storage.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\Response
 */
public function store(Request $request)
{
    // Log the request data for debugging
    \Illuminate\Support\Facades\Log::info('Treatment creation request data:', $request->all());

    try {
        // Validation de base
        $validated = $request->validate([
            'rabbit_selection_type' => 'required|in:individual,litter',
            'medication_id' => 'required|exists:medications,id',
            'scheduled_at' => 'required|date',
            'notes' => 'nullable|string',
            'cost' => 'nullable|numeric|min:0',
            'expense_category' => 'nullable|string',
        ]);

        // Validation conditionnelle selon le type de sélection
        if ($request->rabbit_selection_type === 'individual') {
            $request->validate([
                'rabbit_ids' => 'required|array',
                'rabbit_ids.*' => 'exists:rabbits,id',
            ]);
        } else {
            $request->validate([
                'litter_id' => 'required|exists:breedings,id',
            ]);
        }

        // Créer une dépense si un coût est spécifié
        if ($request->filled('cost') && $request->cost > 0) {
            // Récupérer le nom du médicament
            $medication = Medication::findOrFail($request->medication_id);
            
            // Créer la dépense
            Expense::create([
                'amount' => $request->cost,
                'date' => now(),
                'category' => $request->expense_category ?? 'medication',
                'description' => 'Traitement: ' . $medication->name,
                'notes' => $request->notes
            ]);
        }

        // Process based on selection type
        if ($request->rabbit_selection_type === 'individual') {
            // Vérifier si rabbit_ids est présent et non vide
            if (!$request->has('rabbit_ids') || empty($request->rabbit_ids)) {
                return redirect()->back()
                    ->with('error', 'Veuillez sélectionner au moins un lapin.')
                    ->withInput();
            }

            // Create a treatment for each selected rabbit
            $count = 0;
            foreach ($request->rabbit_ids as $rabbitId) {
                Treatment::create([
                    'rabbit_id' => $rabbitId,
                    'medication_id' => $request->medication_id,
                    'scheduled_at' => $request->scheduled_at,
                    'notes' => $request->notes,
                    'status' => 'pending',
                ]);
                $count++;
            }
            
            $message = $count . ' traitements programmés avec succès.';
        } else {
            // Get the breeding (litter)
            $breeding = Breeding::findOrFail($request->litter_id);
            
            // Récupérer les lapins associés à cette portée
            $rabbits = Rabbit::where('breeding_id', $breeding->id)->get();
            
            // Si aucun lapin n'est trouvé, essayer de récupérer par d'autres moyens
            if ($rabbits->isEmpty()) {
                // Vérifier si la portée a un nombre de lapereaux défini
                if ($breeding->number_of_kits > 0) {
                    // Créer un traitement pour la mère de la portée ou un lapin fictif
                    $motherId = $breeding->mother_id;
                    
                    // Si la mère n'est pas définie, utiliser un lapin par défaut ou créer un enregistrement spécial
                    if (!$motherId) {
                        // Trouver un lapin existant à utiliser comme référence
                        $defaultRabbit = Rabbit::first();
                        
                        if (!$defaultRabbit) {
                            return redirect()->back()
                                ->with('error', 'Impossible de créer un traitement pour une portée sans lapins associés. Veuillez d\'abord créer au moins un lapin.')
                                ->withInput();
                        }
                        
                        $motherId = $defaultRabbit->id;
                    }
                    
                    // Créer un traitement avec un rabbit_id valide
                    Treatment::create([
                        'rabbit_id' => $motherId, // Utiliser l'ID de la mère ou d'un lapin par défaut
                        'breeding_id' => $breeding->id,
                        'medication_id' => $request->medication_id,
                        'scheduled_at' => $request->scheduled_at,
                        'notes' => $request->notes . ' (Portée #' . $breeding->id . ' - ' . $breeding->number_of_kits . ' lapereaux)',
                        'status' => 'pending',
                    ]);
                    
                    $message = 'Traitement programmé pour la portée #' . $breeding->id . ' (' . $breeding->number_of_kits . ' lapereaux).';
                } else {
                    return redirect()->back()
                        ->with('error', 'Aucun lapin trouvé dans cette portée et le nombre de lapereaux n\'est pas défini.')
                        ->withInput();
                }
            } else {
                // Create a treatment for each rabbit in the litter
                foreach ($rabbits as $rabbit) {
                    Treatment::create([
                        'rabbit_id' => $rabbit->id,
                        'medication_id' => $request->medication_id,
                        'scheduled_at' => $request->scheduled_at,
                        'notes' => $request->notes . ' (Portée #' . $breeding->id . ')',
                        'status' => 'pending',
                    ]);
                }
                
                $message = count($rabbits) . ' traitements programmés pour la portée #' . $breeding->id . '.';
            }
        }
        
        return redirect()->route('treatments.index')->with('success', $message);
        
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error('Error creating treatments: ' . $e->getMessage());
        return redirect()->back()
            ->with('error', 'Une erreur est survenue: ' . $e->getMessage())
            ->withInput();
    }
}

    /**
     * Affiche les détails d'un traitement.
     *
     * @param  \App\Models\Treatment  $treatment
     * @return \Illuminate\View\View
     */
    public function show(Treatment $treatment)
    {
        return view('treatments.show', compact('treatment'));
    }

    /**
     * Affiche le formulaire d'édition d'un traitement.
     *
     * @param  \App\Models\Treatment  $treatment
     * @return \Illuminate\View\View
     */
    public function edit(Treatment $treatment)
    {
        $rabbits = Rabbit::all();
        $medications = Medication::all();
        
        return view('treatments.edit', compact('treatment', 'rabbits', 'medications'));
    }

    /**
     * Met à jour un traitement.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Treatment  $treatment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Treatment $treatment)
    {
        $validated = $request->validate([
            'rabbit_id' => 'required|exists:rabbits,id',
            'medication_id' => 'required|exists:medications,id',
            'scheduled_at' => 'required|date',
            'notes' => 'nullable|string',
            'status' => 'required|in:pending,completed,cancelled,skipped',
        ]);
        
        $treatment->update($validated);
        
        return redirect()->route('treatments.index')
            ->with('success', 'Traitement mis à jour avec succès.');
    }

    /**
     * Supprime un traitement.
     *
     * @param  \App\Models\Treatment  $treatment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Treatment $treatment)
    {
        $treatment->delete();
        
        return redirect()->route('treatments.index')
            ->with('success', 'Traitement supprimé avec succès.');
    }
    
    /**
     * Affiche le calendrier des traitements.
     *
     * @return \Illuminate\View\View
     */
    public function calendar()
    {
        // Récupérer les statistiques pour le tableau de bord du calendrier
        $stats = [
            'pending' => Treatment::where('status', 'pending')->count(),
            'completed' => Treatment::where('status', 'completed')->count(),
            'today' => Treatment::where('status', 'pending')
                                ->whereDate('scheduled_at', now()->toDateString())
                                ->count(),
            'overdue' => Treatment::where('status', 'pending')
                             ->where('scheduled_at', '<', now()->startOfDay())
                             ->count(),
        ];
        
        return view('treatments.calendar', compact('stats'));
    }
    
    /**
     * Récupère les événements pour le calendrier.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCalendarEvents(Request $request)
    {
        // Récupérer les dates de début et de fin du calendrier
        $start = $request->input('start');
        $end = $request->input('end');
        
        // Récupérer tous les traitements dans la plage de dates
        $treatments = Treatment::with(['rabbit', 'medication'])
            ->where(function($query) use ($start, $end) {
                $query->whereBetween('scheduled_at', [$start, $end])
                      ->orWhereBetween('completed_at', [$start, $end]);
            })
            ->get();
        
        $events = [];
        
        foreach ($treatments as $treatment) {
            // Déterminer le titre de l'événement
            $title = $treatment->medication->name . ' - ' . $treatment->rabbit->name;
            
            // Déterminer la couleur en fonction du statut
            $color = $this->getStatusColor($treatment->status);
            
            // Déterminer si l'événement est terminé
            $completed = $treatment->status === 'completed' || $treatment->status === 'skipped';
            
            // Créer l'événement
            $events[] = [
                'id' => $treatment->id,
                'title' => $title,
                'start' => $treatment->scheduled_at->format('Y-m-d\TH:i:s'),
                'end' => $treatment->scheduled_at->addHours(1)->format('Y-m-d\TH:i:s'), // Ajouter 1 heure pour la durée
                'allDay' => true,
                'extendedProps' => [
                    'id' => $treatment->id,
                    'rabbit' => $treatment->rabbit->name,
                    'rabbit_id' => $treatment->rabbit->id,
                    'medication' => $treatment->medication->name,
                    'dosage' => $treatment->medication->dosage,
                    'notes' => $treatment->notes,
                    'status' => $treatment->status,
                    'completed_at' => $treatment->completed_at ? $treatment->completed_at->format('Y-m-d H:i:s') : null,
                ],
                'backgroundColor' => $color,
                'borderColor' => $color,
                'textColor' => '#ffffff',
                'classNames' => [
                    $treatment->status,
                    $treatment->scheduled_at->isPast() && $treatment->status === 'pending' ? 'overdue' : '',
                    $treatment->scheduled_at->isToday() && $treatment->status === 'pending' ? 'today' : '',
                ],
                'display' => 'block',
            ];
            
            // Si le traitement est complété, ajouter un second événement pour la date de complétion
            if ($completed && $treatment->completed_at) {
                $events[] = [
                    'id' => 'completed-' . $treatment->id,
                    'title' => '✓ ' . $title,
                    'start' => $treatment->completed_at->format('Y-m-d\TH:i:s'),
                    'end' => $treatment->completed_at->addHours(1)->format('Y-m-d\TH:i:s'),
                    'allDay' => true,
                    'extendedProps' => [
                        'id' => $treatment->id,
                        'rabbit' => $treatment->rabbit->name,
                        'rabbit_id' => $treatment->rabbit->id,
                        'medication' => $treatment->medication->name,
                        'dosage' => $treatment->medication->dosage,
                        'notes' => $treatment->notes,
                        'status' => 'completed_date',
                        'original_status' => $treatment->status,
                    ],
                    'backgroundColor' => '#10B981', // Vert plus foncé pour les traitements complétés
                    'borderColor' => '#10B981',
                    'textColor' => '#ffffff',
                    'classNames' => ['completed-date'],
                    'display' => 'block',
                ];
            }
        }
        
        return response()->json($events);
    }
    
    /**
     * Marque un traitement comme complété.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function markAsDone($id)
    {
        $treatment = Treatment::findOrFail($id);
        
        // Make sure to use quotes around the status value
        // and ensure it matches the expected format in the constraint
        $treatment->status = 'completed';
        $treatment->completed_at = now();
        $treatment->save();
        
        return redirect()->back()->with('success', 'Traitement marqué comme complété.');
    }
    
    /**
     * Marque un traitement comme ignoré.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function markAsSkipped($id)
    {
        $treatment = Treatment::findOrFail($id);
        $treatment->status = 'skipped';
        $treatment->save();
        
        return redirect()->back()->with('success', 'Traitement marqué comme ignoré.');
    }
    
    /**
     * Affiche l'historique des traitements d'un lapin.
     *
     * @param  \App\Models\Rabbit  $rabbit
     * @return \Illuminate\View\View
     */
    public function history(Rabbit $rabbit)
    {
        $treatments = Treatment::where('rabbit_id', $rabbit->id)
            ->with('medication')
            ->orderBy('scheduled_at', 'desc')
            ->paginate(15);
            
        return view('treatments.history', compact('rabbit', 'treatments'));
    }
    
    /**
     * Obtient la couleur correspondant au statut du traitement.
     *
     * @param  string  $status
     * @return string
     */
    private function getStatusColor($status)
    {
        switch ($status) {
            case 'pending':
                return '#FCD34D'; // Jaune
            case 'completed':
                return '#6EE7B7'; // Vert
            case 'cancelled':
                return '#F87171'; // Rouge
            case 'skipped':
                return '#E5E7EB'; // Gris
            default:
                return '#93C5FD'; // Bleu par défaut
        }
    }
}