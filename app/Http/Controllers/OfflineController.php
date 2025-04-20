<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rabbit;
use App\Models\Cage;
use App\Models\Treatment;
use App\Models\Breeding;
use App\Models\FoodSchedule;
use App\Models\Reminder;
use Illuminate\Support\Facades\DB;

class OfflineController extends Controller
{
    public function syncPage()
    {
        return view('offline.sync');
    }

    public function downloadData()
    {
        $user = auth()->user();
        
        // Récupérer toutes les données nécessaires pour l'application hors ligne
        $data = [
            'rabbits' => Rabbit::all(),
            'cages' => Cage::all(),
            'treatments' => Treatment::where('scheduled_at', '>=', now()->subDays(30))->get(),
            'breedings' => Breeding::where('created_at', '>=', now()->subDays(90))->get(),
            'foodSchedules' => FoodSchedule::where('scheduled_at', '>=', now()->subDays(7))->get(),
            'reminders' => Reminder::where('active', true)->get(),
            'user' => $user,
            'timestamp' => now()->timestamp
        ];
        
        return response()->json($data);
    }

    public function uploadData(Request $request)
    {
        $data = $request->json()->all();
        
        DB::beginTransaction();
        
        try {
            // Traiter les données reçues de l'application hors ligne
            if (isset($data['rabbits'])) {
                foreach ($data['rabbits'] as $rabbitData) {
                    if (!isset($rabbitData['id'])) {
                        // Nouveau lapin
                        Rabbit::create($rabbitData);
                    }
                }
            }
            
            // Traiter les autres types de données...
            
            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function offlineApp()
    {
        return view('offline.app');
    }
}