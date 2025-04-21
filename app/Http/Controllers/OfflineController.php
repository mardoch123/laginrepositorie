<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rabbit;
use App\Models\Cage;
use App\Models\Breeding;
use App\Models\Treatment;
use Illuminate\Support\Facades\Auth;
use App\Models\FoodSchedule;
use App\Models\Reminder;

class OfflineController extends Controller
{
    public function syncPage()
    {
        return view('offline.sync');
    }

    /**
     * Download data for offline use
     */
    public function downloadData()
    {
        // Get all data needed for offline use
        $data = [
            'timestamp' => now()->timestamp,
            'rabbits' => Rabbit::where('user_id', Auth::id())->get(),
            'cages' => Cage::where('user_id', Auth::id())->get(),
            'treatments' => Treatment::where('user_id', Auth::id())->get(),
            'breedings' => Breeding::where('user_id', Auth::id())->get(),
        ];

        return response()->json($data);
    }

    /**
     * Upload data from offline mode
     */
    public function uploadData(Request $request)
    {
        // Process uploaded data
        $data = $request->all();
        
        // Handle new or updated rabbits
        if (isset($data['rabbits']) && is_array($data['rabbits'])) {
            foreach ($data['rabbits'] as $rabbitData) {
                if (isset($rabbitData['id']) && $rabbitData['id'] > 0) {
                    // Update existing rabbit
                    $rabbit = Rabbit::find($rabbitData['id']);
                    if ($rabbit && $rabbit->user_id == Auth::id()) {
                        $rabbit->update($rabbitData);
                    }
                } else {
                    // Create new rabbit
                    $rabbitData['user_id'] = Auth::id();
                    Rabbit::create($rabbitData);
                }
            }
        }
        
        // Handle new or updated cages
        if (isset($data['cages']) && is_array($data['cages'])) {
            foreach ($data['cages'] as $cageData) {
                if (isset($cageData['id']) && $cageData['id'] > 0) {
                    // Update existing cage
                    $cage = Cage::find($cageData['id']);
                    if ($cage && $cage->user_id == Auth::id()) {
                        $cage->update($cageData);
                    }
                } else {
                    // Create new cage
                    $cageData['user_id'] = Auth::id();
                    Cage::create($cageData);
                }
            }
        }
        
        // Handle new or updated treatments
        if (isset($data['treatments']) && is_array($data['treatments'])) {
            foreach ($data['treatments'] as $treatmentData) {
                if (isset($treatmentData['id']) && $treatmentData['id'] > 0) {
                    // Update existing treatment
                    $treatment = Treatment::find($treatmentData['id']);
                    if ($treatment && $treatment->user_id == Auth::id()) {
                        $treatment->update($treatmentData);
                    }
                } else {
                    // Create new treatment
                    $treatmentData['user_id'] = Auth::id();
                    Treatment::create($treatmentData);
                }
            }
        }
        
        // Handle new or updated breedings
        if (isset($data['breedings']) && is_array($data['breedings'])) {
            foreach ($data['breedings'] as $breedingData) {
                if (isset($breedingData['id']) && $breedingData['id'] > 0) {
                    // Update existing breeding
                    $breeding = Breeding::find($breedingData['id']);
                    if ($breeding && $breeding->user_id == Auth::id()) {
                        $breeding->update($breedingData);
                    }
                } else {
                    // Create new breeding
                    $breedingData['user_id'] = Auth::id();
                    Breeding::create($breedingData);
                }
            }
        }
        
        return response()->json(['success' => true, 'message' => 'Data synchronized successfully']);
    }

    public function offlineApp()
    {
        return view('offline.app');
    }
    
    public function offlineRabbits()
    {
        return view('offline.rabbits');
    }
    
    public function offlineCages()
    {
        return view('offline.cages');
    }
    
    public function offlineTreatments()
    {
        return view('offline.treatments');
    }
    
    public function offlineBreedings()
    {
        return view('offline.breedings');
    }
    
    // Méthodes privées pour traiter les changements par modèle
    private function processRabbitChange($action, $data)
    {
        // Logique pour traiter les changements de lapins
    }
    
    private function processCageChange($action, $data)
    {
        // Logique pour traiter les changements de cages
    }
    
    private function processTreatmentChange($action, $data)
    {
        // Logique pour traiter les changements de traitements
    }
    
    private function processBreedingChange($action, $data)
    {
        // Logique pour traiter les changements de reproductions
    }
    
    private function processFoodScheduleChange($action, $data)
    {
        // Logique pour traiter les changements de planifications alimentaires
    }
    
    private function processReminderChange($action, $data)
    {
        // Logique pour traiter les changements de rappels
    }
}