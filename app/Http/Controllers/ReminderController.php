<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use App\Models\ReminderLog;
use App\Models\Rabbit;
use App\Models\Litter;
use Illuminate\Http\Request;

class ReminderController extends Controller
{
    public function index()
    {
        $reminders = Reminder::orderBy('due_date')->orderBy('time')->get();
        return view('reminders.index', compact('reminders'));
    }

    public function create()
    {
        $rabbits = Rabbit::all();
        $litters = Litter::all();
        return view('reminders.create', compact('rabbits', 'litters'));
    }

    public function store(Request $request)
    {
        // Log the incoming request data for debugging
        \Illuminate\Support\Facades\Log::info('Reminder creation request:', $request->all());
        
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'due_date' => 'required|date',
                'priority' => 'required|in:low,medium,high,urgent',
                'rabbit_id' => 'nullable|exists:rabbits,id',
                'litter_id' => 'nullable|exists:litters,id',
                'frequency' => 'nullable|in:daily,weekly,custom',
                'time' => 'nullable|date_format:H:i',
                'days_of_week' => 'nullable|array', // Changed from required_if to nullable
                'days_of_week.*' => 'integer|min:0|max:6',
                'interval_days' => 'nullable|integer|min:1', // Changed from required_if to nullable
            ]);

            // Create a new reminder with the validated data
            $reminder = new Reminder();
            $reminder->title = $validated['title'];
            $reminder->description = $validated['description'] ?? null;
            $reminder->due_date = $validated['due_date'];
            $reminder->time = $validated['time'] ?? null;
            $reminder->priority = $validated['priority'];
            $reminder->rabbit_id = $validated['rabbit_id'] ?? null;
            $reminder->litter_id = $validated['litter_id'] ?? null;
            $reminder->frequency = $validated['frequency'] ?? null;
            
            // Handle weekly frequency
            if ($reminder->frequency === 'weekly' && isset($validated['days_of_week'])) {
                $reminder->days_of_week = $validated['days_of_week'];
            }
            
            // Handle custom frequency
            if ($reminder->frequency === 'custom' && isset($validated['interval_days'])) {
                $reminder->interval_days = $validated['interval_days'];
            }
            
            // Set boolean fields
            $reminder->active = $request->has('active');
            $reminder->is_completed = false;
            
            // Save the reminder
            $reminder->save();
            
            \Illuminate\Support\Facades\Log::info('Reminder created successfully with ID: ' . $reminder->id);
            
            return redirect()->route('reminders.index')
                ->with('success', 'Rappel créé avec succès.');
                
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error creating reminder: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Une erreur est survenue lors de la création du rappel: ' . $e->getMessage()]);
        }
    }

    public function show(Reminder $reminder)
    {
        $logs = $reminder->logs()->orderBy('executed_at', 'desc')->paginate(10);
        return view('reminders.show', compact('reminder', 'logs'));
    }

    public function edit(Reminder $reminder)
    {
        $rabbits = Rabbit::all();
        $litters = Litter::all();
        return view('reminders.edit', compact('reminder', 'rabbits', 'litters'));
    }

    public function update(Request $request, Reminder $reminder)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'due_date' => 'nullable|date',
        'time' => 'nullable|date_format:H:i',
        'priority' => 'required|in:low,medium,high,urgent',
        'frequency' => 'nullable|in:daily,weekly,custom',
        'days_of_week' => 'nullable|array',
        'days_of_week.*' => 'integer|min:0|max:6',
        'interval_days' => 'nullable|integer|min:1',
    ]);
    
    // Gérer les cases à cocher (checkbox)
    $reminder->active = $request->has('active');
    $reminder->is_completed = $request->has('is_completed');
    
    // Mettre à jour les autres champs
    $reminder->title = $validated['title'];
    $reminder->description = $validated['description'] ?? null;
    $reminder->due_date = $validated['due_date'] ?? null;
    $reminder->time = $validated['time'] ?? null;
    $reminder->priority = $validated['priority'];
    $reminder->frequency = $validated['frequency'] ?? null;
    
    // Gérer les jours de la semaine pour la fréquence hebdomadaire
    if ($reminder->frequency === 'weekly' && isset($validated['days_of_week'])) {
        $reminder->days_of_week = $validated['days_of_week'];
    } else {
        $reminder->days_of_week = null;
    }
    
    // Gérer l'intervalle pour la fréquence personnalisée
    if ($reminder->frequency === 'custom' && isset($validated['interval_days'])) {
        $reminder->interval_days = $validated['interval_days'];
    } else {
        $reminder->interval_days = null;
    }
    
    $reminder->save();
    
    return redirect()->route('reminders.index')
        ->with('success', 'Rappel mis à jour avec succès.');
}

    public function destroy(Reminder $reminder)
    {
        $reminder->delete();

        return redirect()->route('reminders.index')
            ->with('success', 'Rappel supprimé avec succès.');
    }

    public function toggleActive(Reminder $reminder)
    {
        $reminder->update(['active' => !$reminder->active]);

        return response()->json([
            'success' => true,
            'active' => $reminder->active
        ]);
    }

    public function logs(Reminder $reminder)
    {
        $logs = $reminder->logs()->orderBy('executed_at', 'desc')->paginate(20);
        return view('reminders.logs', compact('reminder', 'logs'));
    }
}