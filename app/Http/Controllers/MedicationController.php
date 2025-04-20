<?php

namespace App\Http\Controllers;

use App\Models\Medication;
use Illuminate\Http\Request;

class MedicationController extends Controller
{
    public function index()
    {
        $medications = Medication::latest()->get();
        return view('medications.index', compact('medications'));
    }

    public function create()
    {
        return view('medications.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'dosage' => 'required|string|max:255',
            'frequency' => 'required|in:daily,weekly,monthly',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'notes' => 'nullable|string',
        ]);

        Medication::create($validated);

        return redirect()->route('medications.index')
            ->with('success', 'Médicament ajouté avec succès.');
    }

    public function show(Medication $medication)
    {
        return view('medications.show', compact('medication'));
    }

    public function edit(Medication $medication)
    {
        return view('medications.edit', compact('medication'));
    }

    public function update(Request $request, Medication $medication)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'dosage' => 'required|string|max:255',
            'frequency' => 'required|in:daily,weekly,monthly',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'notes' => 'nullable|string',
        ]);

        $medication->update($validated);

        return redirect()->route('medications.index')
            ->with('success', 'Médicament mis à jour avec succès.');
    }

    public function destroy(Medication $medication)
    {
        $medication->delete();

        return redirect()->route('medications.index')
            ->with('success', 'Médicament supprimé avec succès.');
    }
}