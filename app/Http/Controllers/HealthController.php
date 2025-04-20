<?php

namespace App\Http\Controllers;

use App\Models\Rabbit;
use App\Models\Illness;
use App\Models\Treatment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Breeding;
use App\Models\Mortality;

class HealthController extends Controller
{
    /**
     * Affiche le tableau de bord de santé
     */
    public function dashboard()
    {
        // Statistiques générales
        $totalRabbits = Rabbit::count();
        $aliveRabbits = Rabbit::where('status', 'alive')->count();
        $deadRabbits = Rabbit::where('status', 'dead')->count();
        $mortalityRate = $totalRabbits > 0 ? round(($deadRabbits / $totalRabbits) * 100, 1) : 0;
        
        // Statistiques de mortalité par mois
        $mortalityByMonth = $this->getMortalityByMonth();
        
        // Statistiques de maladies
        $illnessesByType = $this->getIllnessesByType();
        $activeIllnesses = Illness::where('status', 'active')->count();
        $curedIllnesses = Illness::where('status', 'cured')->count();
        
        // Traitements
        $activeTreatments = Treatment::where('status', 'active')->count();
        $completedTreatments = Treatment::where('status', 'completed')->count();
        
        return view('health.dashboard', compact(
            'totalRabbits',
            'aliveRabbits',
            'deadRabbits',
            'mortalityRate',
            'mortalityByMonth',
            'illnessesByType',
            'activeIllnesses',
            'curedIllnesses',
            'activeTreatments',
            'completedTreatments'
        ));
    }
    
    /**
     * Affiche la liste des signalements de mortalité
     */
    public function mortalityIndex()
    {
        $deadRabbits = Rabbit::with('cage')  // Suppression de 'breed'
            ->where('status', 'dead')
            ->orderBy('death_date', 'desc')
            ->paginate(15);
            
        return view('health.mortality.index', compact('deadRabbits'));
    }
    
    /**
     * Affiche le formulaire pour signaler un décès
     */
    /**
     * Affiche le formulaire pour enregistrer un décès
     */
    public function mortalityCreate()
    {
        // Récupérer tous les lapins vivants
        $rabbits = Rabbit::where('status', '!=', 'dead')
                        ->where('status', '!=', 'sold')
                        ->get();
        
        // Récupérer toutes les portées actives
        $litters = Breeding::whereIn('status', ['active', 'weaned', 'fattening'])
                        ->with(['mother', 'father'])
                        ->get();
        
        // Récupérer les maladies actives
        $illnesses = Illness::where('status', 'active')
                        ->orWhere('status', 'chronic')
                        ->get();
        
        return view('health.mortality.create', compact('rabbits', 'litters', 'illnesses'));
    }
    
    /**
     * Enregistre un signalement de mortalité
     */
    /**
     * Enregistre un nouveau décès.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function mortalityStore(Request $request)
    {
        // Validation de base
        $rules = [
            'selection_type' => 'required|in:individual,litter',
            'death_date' => 'required|date',
            'death_cause' => 'required|string',
            'notes' => 'nullable|string',
        ];
        
        // Validation conditionnelle selon le type de sélection
        if ($request->selection_type === 'individual') {
            $rules['rabbit_id'] = 'required|exists:rabbits,id';
        } else {
            $rules['litter_id'] = 'required|exists:breedings,id';
            $rules['kit_count'] = 'required|integer|min:1';
            $rules['kit_sex'] = 'required|in:unknown,male,female,mixed';
        }
        
        // Validation conditionnelle pour la maladie
        if ($request->death_cause === 'illness') {
            $rules['illness_id'] = 'required|exists:illnesses,id';
        }
        
        $validated = $request->validate($rules);
        
        // Traitement selon le type de sélection
        if ($request->selection_type === 'individual') {
            // Traitement pour un lapin individuel
            $rabbit = Rabbit::findOrFail($request->rabbit_id);
            
            // Enregistrer le décès
            $mortality = new Mortality();
            $mortality->rabbit_id = $rabbit->id;
            $mortality->death_date = $request->death_date;
            $mortality->death_cause = $request->death_cause;
            $mortality->notes = $request->notes;
            
            if ($request->death_cause === 'illness' && $request->illness_id) {
                $mortality->illness_id = $request->illness_id;
            }
            
            $mortality->save();
            
            // Mettre à jour le statut du lapin
            $rabbit->status = 'dead';
            $rabbit->save();
        } else {
            // Traitement pour une portée
            $breeding = Breeding::findOrFail($request->litter_id);
            
            // Enregistrer le décès pour la portée
            $mortality = new Mortality();
            $mortality->breeding_id = $breeding->id;
            $mortality->death_date = $request->death_date;
            $mortality->death_cause = $request->death_cause;
            $mortality->kit_count = $request->kit_count;
            $mortality->kit_sex = $request->kit_sex;
            $mortality->notes = $request->notes;
            
            if ($request->death_cause === 'illness' && $request->illness_id) {
                $mortality->illness_id = $request->illness_id;
            }
            
            $mortality->save();
            
            // Mettre à jour le nombre de lapereaux dans la portée
            $breeding->number_of_kits = max(0, $breeding->number_of_kits - $request->kit_count);
            
            // Si tous les lapereaux sont morts, mettre à jour le statut de la portée
            if ($breeding->number_of_kits <= 0) {
                $breeding->status = 'completed';
            }
            
            $breeding->save();
        }
        
        return redirect()->route('health.mortality.index')->with('success', 'Décès enregistré avec succès.');
    }
    
    /**
     * Affiche la liste des maladies
     */
    public function illnessIndex()
    {
        $illnesses = Illness::with('rabbit')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('health.illness.index', compact('illnesses'));
    }
    
    /**
     * Affiche le formulaire pour signaler une maladie
     */
    public function illnessCreate()
    {
        $rabbits = Rabbit::where('status', 'alive')->orderBy('name')->get();
        $illnessTypes = $this->getIllnessTypes();
        $symptoms = $this->getSymptoms();
        
        return view('health.illness.create', compact('rabbits', 'illnessTypes', 'symptoms'));
    }
    
    /**
     * Enregistre un signalement de maladie
     */
    public function illnessStore(Request $request)
    {
        $validated = $request->validate([
            'rabbit_id' => 'required|exists:rabbits,id',
            'type' => 'required|string',
            'severity' => 'required|in:mild,moderate,severe',
            'detection_date' => 'required|date',
            'status' => 'required|in:active,recovered,chronic,fatal',
            'recovery_date' => 'nullable|date|required_if:status,recovered',
            'symptoms' => 'required|string',
            'notes' => 'nullable|string',
        ]);
        
        $illness = new Illness();
        $illness->rabbit_id = $validated['rabbit_id'];
        $illness->type = $validated['type'];
        $illness->symptoms = $validated['symptoms'];
        $illness->detection_date = $validated['detection_date'];
        $illness->severity = $validated['severity'];
        $illness->notes = $validated['notes'];
        $illness->status = $validated['status'];
        
        if ($validated['status'] == 'recovered' && isset($validated['recovery_date'])) {
            $illness->recovery_date = $validated['recovery_date'];
        }
        
        $illness->save();
        
        // Créer un traitement si nécessaire
        if ($request->has('treatment_started') && $request->treatment_started) {
            $treatment = new Treatment();
            $treatment->rabbit_id = $validated['rabbit_id'];
            $treatment->illness_id = $illness->id;
            $treatment->description = $validated['treatment_description'];
            $treatment->start_date = now();
            $treatment->status = 'active';
            $treatment->save();
        }
        
        return redirect()->route('health.illness.index')
            ->with('success', 'Maladie signalée avec succès.');
    }
    
    /**
     * Affiche les détails d'une maladie
     */
    public function illnessShow(Illness $illness)
    {
        $treatments = Treatment::where('illness_id', $illness->id)->get();
        $illnessTypes = $this->getIllnessTypes();
        $symptoms = $this->getSymptoms();
        
        return view('health.illness.show', compact('illness', 'treatments', 'illnessTypes', 'symptoms'));
    }
    
    /**
     * Affiche le formulaire d'édition d'une maladie
     */
    public function illnessEdit(Illness $illness)
    {
        $illnessTypes = $this->getIllnessTypes();
        $symptoms = $this->getSymptoms();
        $treatments = Treatment::where('illness_id', $illness->id)->get();
        
        return view('health.illness.edit', compact('illness', 'illnessTypes', 'symptoms', 'treatments'));
    }
    
    /**
     * Met à jour une maladie
     */
    public function illnessUpdate(Request $request, Illness $illness)
    {
        $validated = $request->validate([
            'type' => 'required|string',
            'symptoms' => 'required|array',
            'symptoms.*' => 'string',
            'severity' => 'required|in:mild,moderate,severe',
            'notes' => 'nullable|string',
            'status' => 'required|in:active,cured,fatal',
            'cure_date' => 'nullable|required_if:status,cured|date|before_or_equal:today',
        ]);
        
        $illness->type = $validated['type'];
        $illness->symptoms = json_encode($validated['symptoms']);
        $illness->severity = $validated['severity'];
        $illness->notes = $validated['notes'];
        $illness->status = $validated['status'];
        
        if ($validated['status'] == 'cured' && isset($validated['cure_date'])) {
            $illness->cure_date = $validated['cure_date'];
            
            // Mettre à jour les traitements associés
            Treatment::where('illness_id', $illness->id)
                ->where('status', 'active')
                ->update([
                    'status' => 'completed',
                    'end_date' => $validated['cure_date']
                ]);
        }
        
        if ($validated['status'] == 'fatal') {
            // Vérifier si le lapin est déjà marqué comme mort
            $rabbit = Rabbit::find($illness->rabbit_id);
            if ($rabbit && $rabbit->status != 'dead') {
                $rabbit->status = 'dead';
                $rabbit->death_date = now();
                $rabbit->death_cause = 'illness';
                $rabbit->death_notes = 'Décès suite à ' . $this->getIllnessTypes()[$illness->type];
                $rabbit->save();
                
                // Libérer la cage si nécessaire
                if ($rabbit->cage) {
                    $rabbit->cage->updateOccupancy();
                }
            }
        }
        
        $illness->save();
        
        return redirect()->route('health.illness.index')
            ->with('success', 'Maladie mise à jour avec succès.');
    }
    
    /**
     * Supprime une maladie
     */
    public function illnessDestroy(Illness $illness)
    {
        // Supprimer les traitements associés
        Treatment::where('illness_id', $illness->id)->delete();
        
        $illness->delete();
        
        return redirect()->route('health.illness.index')
            ->with('success', 'Maladie supprimée avec succès.');
    }
    
    /**
     * Retourne les causes de décès possibles
     */
    private function getDeathCauses()
    {
        return [
            'illness' => 'Maladie',
            'accident' => 'Accident',
            'predator' => 'Prédateur',
            'slaughter' => 'Abattage',
            'old_age' => 'Vieillesse',
            'birth_complication' => 'Complication à la naissance',
            'unknown' => 'Cause inconnue',
            'other' => 'Autre'
        ];
    }
    
    /**
     * Retourne les types de maladies possibles
     */
    private function getIllnessTypes()
    {
        return [
            'coccidiosis' => 'Coccidiose',
            'pasteurellosis' => 'Pasteurellose',
            'myxomatosis' => 'Myxomatose',
            'vhd' => 'Maladie hémorragique virale (VHD)',
            'ear_mites' => 'Gale des oreilles',
            'fur_mites' => 'Gale du pelage',
            'diarrhea' => 'Diarrhée',
            'respiratory' => 'Problème respiratoire',
            'eye_infection' => 'Infection oculaire',
            'abscess' => 'Abcès',
            'dental_problem' => 'Problème dentaire',
            'digestive' => 'Trouble digestif',
            'other' => 'Autre'
        ];
    }
    
    /**
     * Retourne les symptômes possibles
     */
    private function getSymptoms()
    {
        return [
            'diarrhea' => 'Diarrhée',
            'constipation' => 'Constipation',
            'loss_of_appetite' => 'Perte d\'appétit',
            'weight_loss' => 'Perte de poids',
            'lethargy' => 'Léthargie',
            'fever' => 'Fièvre',
            'sneezing' => 'Éternuements',
            'nasal_discharge' => 'Écoulement nasal',
            'eye_discharge' => 'Écoulement oculaire',
            'swollen_eyes' => 'Yeux gonflés',
            'head_tilt' => 'Tête penchée',
            'drooling' => 'Bave excessive',
            'difficulty_breathing' => 'Difficulté à respirer',
            'skin_lesions' => 'Lésions cutanées',
            'fur_loss' => 'Perte de poils',
            'scratching' => 'Grattage excessif',
            'swelling' => 'Gonflement',
            'paralysis' => 'Paralysie',
            'seizures' => 'Convulsions',
            'abnormal_behavior' => 'Comportement anormal'
        ];
    }
    
    /**
     * Retourne les statistiques de mortalité par mois
     */
    private function getMortalityByMonth()
    {
        $result = [];
        $currentYear = Carbon::now()->year;
        
        for ($month = 1; $month <= 12; $month++) {
            $count = Rabbit::where('status', 'dead')
                ->whereYear('death_date', $currentYear)
                ->whereMonth('death_date', $month)
                ->count();
                
            $result[] = [
                'month' => Carbon::createFromDate($currentYear, $month, 1)->format('M'),
                'count' => $count
            ];
        }
        
        return $result;
    }
    
    /**
     * Retourne les statistiques de maladies par type
     */
    private function getIllnessesByType()
    {
        $types = $this->getIllnessTypes();
        $result = [];
        
        foreach ($types as $key => $name) {
            $count = Illness::where('type', $key)->count();
            
            if ($count > 0) {
                $result[] = [
                    'type' => $name,
                    'count' => $count
                ];
            }
        }
        
        return $result;
    }
}