<?php

namespace App\Http\Controllers;

use App\Models\Rabbit;
use App\Models\Breeding;
use App\Models\Litter;
use App\Models\HealthRecord;
use App\Models\Diagnostic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class DiagnosticController extends Controller
{
    /**
     * Constructeur avec middleware auth
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $diagnostics = Diagnostic::with('rabbit')->orderBy('created_at', 'desc')->paginate(10);
        $rabbits = Rabbit::all(); // Ajout de cette ligne pour définir $rabbits
        return view('diagnostics.index', compact('diagnostics', 'rabbits'));
    }
    
    public function create()
    {
        $rabbits = Rabbit::all();
        return view('diagnostics.create', compact('rabbits'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'rabbit_id' => 'required|exists:rabbits,id',
            'symptoms' => 'required|string|min:10',
            'observed_date' => 'required|date',
            'additional_notes' => 'nullable|string',
            'temperature' => 'nullable|numeric',
            'weight' => 'nullable|numeric',
            'appetite_level' => 'nullable|string|in:normal,reduced,none',
            'activity_level' => 'nullable|string|in:normal,reduced,lethargic',
        ]);
        
        // Créer le diagnostic
        $diagnostic = Diagnostic::create($validated);
        
        // Générer le diagnostic IA de manière asynchrone
        try {
            // Récupérer les données du lapin avec ses relations
            $rabbit = Rabbit::with(['healthRecords', 'mother', 'father', 'litter'])->find($diagnostic->rabbit_id);
            
            // Générer le diagnostic IA
            $aiDiagnosis = $this->generateAIDiagnosis($diagnostic, $rabbit);
            $diagnostic->ai_diagnosis = $aiDiagnosis;
            $diagnostic->save();
        } catch (\Exception $e) {
            // Enregistrer l'erreur mais continuer
            \Log::error('Erreur lors de la génération du diagnostic IA: ' . $e->getMessage());
            $diagnostic->ai_diagnosis = "Erreur lors de la génération du diagnostic IA. Veuillez réessayer ultérieurement.";
            $diagnostic->save();
        }
        
        // Si c'est une requête AJAX, renvoyer une réponse JSON
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Diagnostic créé avec succès.',
                'redirect' => route('diagnostics.show', $diagnostic)
            ]);
        }
        
        // Sinon, rediriger normalement
        return redirect()->route('diagnostics.show', $diagnostic)
            ->with('success', 'Diagnostic créé avec succès.');
    }
    
    public function show(Diagnostic $diagnostic)
    {
        $rabbit = $diagnostic->rabbit;
        $healthHistory = HealthRecord::where('rabbit_id', $rabbit->id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('diagnostics.show', compact('diagnostic', 'rabbit', 'healthHistory'));
    }
    
    // Modifier la méthode generateAIDiagnosis pour accepter le lapin comme paramètre
    private function generateAIDiagnosis(Diagnostic $diagnostic, Rabbit $rabbit)
    {
        try {
            // Collecter les données pour l'IA
            $data = [
                'rabbit' => [
                    'id' => $rabbit->id,
                    'name' => $rabbit->name,
                    'breed' => $rabbit->breed,
                    'gender' => $rabbit->gender,
                    'age' => $rabbit->birth_date ? \Carbon\Carbon::parse($rabbit->birth_date)->diffInDays(\Carbon\Carbon::now()) : null,
                    'weight' => $diagnostic->weight ?? $rabbit->weight,
                ],
                'symptoms' => $diagnostic->symptoms,
                'temperature' => $diagnostic->temperature,
                'appetite' => $diagnostic->appetite_level,
                'activity' => $diagnostic->activity_level,
                'additional_notes' => $diagnostic->additional_notes,
            ];
            
            // Vérifier si healthRecords existe et n'est pas null
            if (method_exists($rabbit, 'healthRecords') && $rabbit->healthRecords !== null) {
                $data['health_history'] = $rabbit->healthRecords->map(function($record) {
                    return [
                        'date' => $record->created_at->format('Y-m-d'),
                        'condition' => $record->condition,
                        'treatment' => $record->treatment ?? '',
                        'outcome' => $record->outcome ?? '',
                    ];
                });
            } else {
                $data['health_history'] = [];
            }
            
            // Vérifier si la relation mother existe et n'est pas null
            if (method_exists($rabbit, 'mother') && $rabbit->mother !== null) {
                $data['mother'] = [
                    'id' => $rabbit->mother->id,
                    'name' => $rabbit->mother->name,
                    'health_status' => $rabbit->mother->status ?? 'unknown',
                ];
            }
            
            // Vérifier si la relation father existe et n'est pas null
            if (method_exists($rabbit, 'father') && $rabbit->father !== null) {
                $data['father'] = [
                    'id' => $rabbit->father->id,
                    'name' => $rabbit->father->name,
                    'health_status' => $rabbit->father->status ?? 'unknown',
                ];
            }
            
            // Vérifier si la relation litter existe et n'est pas null
            if (method_exists($rabbit, 'litter') && $rabbit->litter !== null) {
                $data['litter'] = [
                    'id' => $rabbit->litter->id,
                    'size' => $rabbit->litter->size,
                    'birth_date' => $rabbit->litter->birth_date ? $rabbit->litter->birth_date->format('Y-m-d') : null,
                ];
            }
            
            // Appel à l'API Gemini ou autre service d'IA
            $response = $this->callAIService($data);
            
            return $response;
        } catch (\Exception $e) {
            \Log::error('Erreur dans generateAIDiagnosis: ' . $e->getMessage());
            throw $e; // Relancer l'exception pour la gestion dans la méthode appelante
        }
    }
    
    private function getFamilyHealthHistory(Rabbit $rabbit)
    {
        $familyHistory = [];
        
        // Historique de santé de la mère
        if ($rabbit->mother) {
            $familyHistory['mother'] = [
                'id' => $rabbit->mother->id,
                'name' => $rabbit->mother->name,
                'health_records' => $rabbit->mother->healthRecords->map(function($record) {
                    return [
                        'date' => $record->created_at->format('Y-m-d'),
                        'condition' => $record->condition,
                        'treatment' => $record->treatment,
                    ];
                }),
            ];
        }
        
        // Historique de santé du père
        if ($rabbit->father) {
            $familyHistory['father'] = [
                'id' => $rabbit->father->id,
                'name' => $rabbit->father->name,
                'health_records' => $rabbit->father->healthRecords->map(function($record) {
                    return [
                        'date' => $record->created_at->format('Y-m-d'),
                        'condition' => $record->condition,
                        'treatment' => $record->treatment,
                    ];
                }),
            ];
        }
        
        // Historique de santé des frères et sœurs (même portée)
        if ($rabbit->litter) {
            $siblings = Rabbit::where('litter_id', $rabbit->litter_id)
                ->where('id', '!=', $rabbit->id)
                ->with('healthRecords')
                ->get();
                
            $familyHistory['siblings'] = $siblings->map(function($sibling) {
                return [
                    'id' => $sibling->id,
                    'name' => $sibling->name,
                    'health_records' => $sibling->healthRecords->map(function($record) {
                        return [
                            'date' => $record->created_at->format('Y-m-d'),
                            'condition' => $record->condition,
                            'treatment' => $record->treatment,
                        ];
                    }),
                ];
            });
        }
        
        return $familyHistory;
    }
    
    private function callGeminiAPI($data)
    {
        // Configuration de l'API Gemini
        $apiKey = config('services.gemini.api_key');
        $endpoint = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-pro-exp-03-25:generateContent';
        
        // Préparer le prompt pour Gemini
        $prompt = $this->prepareGeminiPrompt($data);
        
        try {
            // Appel à l'API avec un timeout augmenté à 60 secondes
            $response = Http::timeout(60)->withHeaders([
                'Content-Type' => 'application/json',
            ])->post($endpoint . '?key=' . $apiKey, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.4,
                    'topK' => 32,
                    'topP' => 0.95,
                    'maxOutputTokens' => 4096,
                ]
            ]);
            
            if ($response->successful()) {
                $result = $response->json();
                return $result['candidates'][0]['content']['parts'][0]['text'] ?? 'Aucun diagnostic généré.';
            } else {
                // Journaliser l'erreur avec plus de détails
                \Log::error('Erreur API Gemini: ' . $response->body());
                
                // Vérifier si c'est une erreur de quota
                $error = $response->json()['error'] ?? [];
                if (isset($error['code']) && $error['code'] == 429) {
                    throw new \Exception('Limite de quota atteinte pour l\'API Gemini. Veuillez réessayer plus tard.');
                }
                
                throw new \Exception('Erreur API: ' . $response->body());
            }
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            // Gestion spécifique des erreurs de connexion
            \Log::error('Erreur de connexion à l\'API Gemini: ' . $e->getMessage());
            throw new \Exception('Impossible de se connecter à l\'API Gemini. Veuillez vérifier votre connexion internet et réessayer.');
        } catch (\Exception $e) {
            // Capturer toutes les autres exceptions
            \Log::error('Exception lors de l\'appel à l\'API Gemini: ' . $e->getMessage());
            throw $e;
        }
    }
    
    private function prepareGeminiPrompt($data)
    {
        // Construire un prompt détaillé pour Gemini
        $prompt = "Tu es un vétérinaire spécialisé dans les lapins. Analyse les informations suivantes et propose un diagnostic détaillé avec des traitements possibles.\n\n";
        
        // Informations sur le lapin
        $prompt .= "INFORMATIONS SUR LE LAPIN:\n";
        $prompt .= "ID: " . $data['rabbit']['id'] . "\n";
        $prompt .= "Nom: " . $data['rabbit']['name'] . "\n";
        $prompt .= "Race: " . $data['rabbit']['breed'] . "\n";
        $prompt .= "Sexe: " . $data['rabbit']['gender'] . "\n";
        
        if ($data['rabbit']['age']) {
            $ageInMonths = round($data['rabbit']['age'] / 30, 1);
            $prompt .= "Âge: " . $ageInMonths . " mois\n";
        }
        
        if ($data['rabbit']['weight']) {
            $prompt .= "Poids: " . $data['rabbit']['weight'] . " kg\n";
        }
        
        // Symptômes actuels
        $prompt .= "\nSYMPTÔMES ACTUELS:\n" . $data['symptoms'] . "\n";
        
        if ($data['temperature']) {
            $prompt .= "Température: " . $data['temperature'] . "°C\n";
        }
        
        if ($data['appetite']) {
            $prompt .= "Appétit: " . $data['appetite'] . "\n";
        }
        
        if ($data['activity']) {
            $prompt .= "Niveau d'activité: " . $data['activity'] . "\n";
        }
        
        if ($data['additional_notes']) {
            $prompt .= "Notes supplémentaires: " . $data['additional_notes'] . "\n";
        }
        
        // Historique de santé
        if (count($data['health_history']) > 0) {
            $prompt .= "\nHISTORIQUE DE SANTÉ DU LAPIN:\n";
            foreach ($data['health_history'] as $record) {
                $prompt .= "- Date: " . $record['date'] . ", Condition: " . $record['condition'] . ", Traitement: " . $record['treatment'] . "\n";
            }
        }
        
        // Historique familial
        if (!empty($data['family_history'])) {
            $prompt .= "\nHISTORIQUE FAMILIAL:\n";
            
            if (isset($data['family_history']['mother'])) {
                $prompt .= "Mère (ID: " . $data['family_history']['mother']['id'] . "):\n";
                foreach ($data['family_history']['mother']['health_records'] as $record) {
                    $prompt .= "- Date: " . $record['date'] . ", Condition: " . $record['condition'] . "\n";
                }
            }
            
            if (isset($data['family_history']['father'])) {
                $prompt .= "Père (ID: " . $data['family_history']['father']['id'] . "):\n";
                foreach ($data['family_history']['father']['health_records'] as $record) {
                    $prompt .= "- Date: " . $record['date'] . ", Condition: " . $record['condition'] . "\n";
                }
            }
            
            if (isset($data['family_history']['siblings']) && count($data['family_history']['siblings']) > 0) {
                $prompt .= "Frères et sœurs:\n";
                foreach ($data['family_history']['siblings'] as $sibling) {
                    $prompt .= "Lapin (ID: " . $sibling['id'] . "):\n";
                    foreach ($sibling['health_records'] as $record) {
                        $prompt .= "- Date: " . $record['date'] . ", Condition: " . $record['condition'] . "\n";
                    }
                }
            }
        }
        
        $prompt .= "\nBASÉ SUR CES INFORMATIONS, FOURNIS:\n";
        $prompt .= "1. Un diagnostic détaillé avec plusieurs possibilités classées par probabilité\n";
        $prompt .= "2. Des traitements recommandés pour chaque diagnostic possible\n";
        $prompt .= "3. Des mesures préventives et des soins à apporter\n";
        $prompt .= "4. Des signes d'alerte à surveiller\n";
        $prompt .= "5. Un pronostic basé sur le diagnostic le plus probable\n\n";
        $prompt .= "Présente ta réponse de manière structurée avec des sections claires.";
        
        return $prompt;
    }



    private function callAIService($data)
    {
        // Par défaut, utiliser Gemini
        return $this->callGeminiAPI($data);
        
        // Si vous souhaitez ajouter d'autres services d'IA à l'avenir, vous pourriez
        // implémenter une logique de sélection ici, par exemple :
        /*
        $aiService = config('services.ai.default', 'gemini');
        
        switch ($aiService) {
            case 'gemini':
                return $this->callGeminiAPI($data);
            case 'openai':
                return $this->callOpenAIAPI($data);
            default:
                return $this->callGeminiAPI($data);
        }
        */
    }
    
    /**
     * Affiche une version imprimable du diagnostic
     *
     * @param  \App\Models\Diagnostic  $diagnostic
     * @return \Illuminate\Http\Response
     */
    public function print(Diagnostic $diagnostic)
    {
        $rabbit = $diagnostic->rabbit;
        $healthHistory = $rabbit->healthRecords ?? collect([]);
        
        return view('diagnostics.print', compact('diagnostic', 'rabbit', 'healthHistory'));
    }
    
    /**
     * Supprime plusieurs diagnostics à la fois.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bulkDelete(Request $request)
    {
        $ids = json_decode($request->input('selected_ids'), true);
        
        if (!is_array($ids) || empty($ids)) {
            return back()->with('error', 'Aucun diagnostic sélectionné pour la suppression.');
        }
        
        // Vérifier que l'utilisateur a le droit de supprimer ces diagnostics
        $count = Diagnostic::whereIn('id', $ids)->count();
        
        if ($count !== count($ids)) {
            return back()->with('error', 'Certains diagnostics sélectionnés n\'existent pas ou ne peuvent pas être supprimés.');
        }
        
        Diagnostic::whereIn('id', $ids)->delete();
        
        return back()->with('success', $count . ' diagnostic(s) supprimé(s) avec succès.');
    }


    
    /**
     * Supprime un diagnostic spécifique.
     *
     * @param  \App\Models\Diagnostic  $diagnostic
     * @return \Illuminate\Http\Response
     */
    public function destroy(Diagnostic $diagnostic)
    {
        // Vérifier si l'utilisateur est autorisé à supprimer ce diagnostic
        // Vous pouvez ajouter une logique d'autorisation ici si nécessaire
        
        // Supprimer le diagnostic
        $diagnostic->delete();
        
        // Rediriger avec un message de succès
        return redirect()->route('diagnostics.index')
            ->with('success', 'Le diagnostic a été supprimé avec succès.');
    }

    /**
     * Update the specified diagnostic in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Diagnostic  $diagnostic
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Diagnostic $diagnostic)
    {
        $validated = $request->validate([
            'veterinarian_notes' => 'nullable|string',
            'treatment_plan' => 'nullable|string',
            'follow_up_date' => 'nullable|date',
        ]);
    
        $diagnostic->update($validated);
    
        return redirect()->route('diagnostics.show', $diagnostic)
                     ->with('success', 'Diagnostic mis à jour avec succès.');
    }
}