<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rabbit;
use App\Models\Treatment;
use Carbon\Carbon;
use Database\Seeders\TreatmentProtocolSeeder;

class ProtocolController extends Controller
{
    /**
     * Affiche la liste des protocoles disponibles
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $protocolSeeder = new TreatmentProtocolSeeder();
        $protocols = $protocolSeeder->getProtocols();
        
        // Récupérer les traitements en cours
        $activeProtocols = Treatment::where('status', 'pending')
            ->orderBy('scheduled_at')
            ->with('rabbit', 'medication')
            ->get()
            ->groupBy('rabbit_id');
            
        return view('protocols.index', compact('protocols', 'activeProtocols'));
    }
    
    /**
     * Affiche le formulaire pour appliquer un protocole à un lapin
     *
     * @return \Illuminate\View\View
     */
    // Dans la méthode create
    public function create()
    {
        $protocols = $this->getAvailableProtocols();
        $rabbits = Rabbit::all();
        $litters = Breeding::with(['mother', 'kits'])->where('status', 'active')->get();
        
        return view('protocols.create', compact('protocols', 'rabbits', 'litters'));
    }
    
    // Dans la méthode store
    public function store(Request $request)
    {
        $validated = $request->validate([
            'protocol_name' => 'required|string',
            'rabbit_selection_type' => 'required|in:individual,litter',
            'rabbit_ids' => 'required_if:rabbit_selection_type,individual|array',
            'rabbit_ids.*' => 'exists:rabbits,id',
            'litter_id' => 'required_if:rabbit_selection_type,litter|exists:breedings,id',
            'start_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);
        
        $protocol = $this->findProtocolByName($validated['protocol_name']);
        
        if (!$protocol) {
            return redirect()->back()->with('error', 'Protocole non trouvé.');
        }
        
        $rabbitIds = [];
        
        if ($request->rabbit_selection_type === 'individual') {
            $rabbitIds = $request->rabbit_ids;
        } else {
            // Récupérer tous les lapereaux de la portée
            $litter = Breeding::findOrFail($request->litter_id);
            $rabbitIds = $litter->kits->pluck('id')->toArray();
        }
        
        $startDate = Carbon::parse($validated['start_date']);
        $createdCount = 0;
        
        foreach ($rabbitIds as $rabbitId) {
            foreach ($protocol['treatments'] as $treatmentData) {
                $scheduledDate = $startDate->copy()->addDays($treatmentData['days_after']);
                
                $medication = Medication::where('name', $treatmentData['medication'])->first();
                
                if (!$medication) {
                    // Créer le médicament s'il n'existe pas
                    $medication = Medication::create([
                        'name' => $treatmentData['medication'],
                        'dosage' => $treatmentData['dosage'] ?? 'Selon prescription',
                        'description' => 'Créé automatiquement par le protocole ' . $protocol['name'],
                    ]);
                }
                
                Treatment::create([
                    'rabbit_id' => $rabbitId,
                    'medication_id' => $medication->id,
                    'scheduled_at' => $scheduledDate,
                    'notes' => ($validated['notes'] ? $validated['notes'] . ' - ' : '') . 'Protocole: ' . $protocol['name'] . ' (Jour ' . $treatmentData['days_after'] . ')',
                    'status' => 'pending',
                ]);
                
                $createdCount++;
            }
        }
        
        return redirect()->route('protocols.index')
            ->with('success', $createdCount . ' traitement(s) créé(s) avec succès pour le protocole ' . $protocol['name'] . '.');
    }
    
    /**
     * Affiche les détails d'un protocole spécifique
     *
     * @param  string  $name
     * @return \Illuminate\View\View
     */
    public function show($name)
    {
        $protocolSeeder = new TreatmentProtocolSeeder();
        $protocols = $protocolSeeder->getProtocols();
        $protocol = collect($protocols)->firstWhere('name', $name);
        
        if (!$protocol) {
            abort(404, "Protocole non trouvé");
        }
        
        return view('protocols.show', compact('protocol'));
    }
    
    /**
     * Marque un traitement comme complété
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function complete($id)
    {
        $treatment = Treatment::findOrFail($id);
        $treatment->status = 'completed';
        $treatment->completed_at = Carbon::now();
        $treatment->save();
        
        return redirect()->back()->with('success', 'Traitement marqué comme complété.');
    }
    
    /**
     * Annule un traitement
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel($id)
    {
        $treatment = Treatment::findOrFail($id);
        $treatment->status = 'cancelled';
        $treatment->save();
        
        return redirect()->back()->with('success', 'Traitement annulé.');
    }
}