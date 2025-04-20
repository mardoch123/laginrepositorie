<?php

namespace App\Http\Controllers;

use App\Models\Food;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    public function index()
    {
        $foods = Food::orderBy('name')->paginate(10);
        return view('foods.index', compact('foods'));
    }

    public function create()
    {
        return view('foods.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'frequency' => 'required|string|in:daily,alternate_days,weekly,weekdays,weekends',
            'quantity_per_rabbit' => 'required|numeric|min:0',
            'unit' => 'required|string|in:g,kg,ml,l',
            'is_active' => 'sometimes|boolean',
            'notes' => 'nullable|string',
        ]);

        // Gérer la case à cocher is_active
        $validated['is_active'] = $request->has('is_active');

        Food::create($validated);

        return redirect()->route('foods.index')
            ->with('success', 'Nourriture ajoutée avec succès.');
    }

    public function show(Food $food)
    {
        $food->load('schedules');
        return view('foods.show', compact('food'));
    }

    public function edit(Food $food)
    {
        return view('foods.edit', compact('food'));
    }

    public function update(Request $request, Food $food)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'frequency' => 'required|string|in:daily,alternate_days,weekly,weekdays,weekends',
            'quantity_per_rabbit' => 'required|numeric|min:0',
            'unit' => 'required|string|in:g,kg,ml,l',
            'is_active' => 'sometimes|boolean',
            'notes' => 'nullable|string',
        ]);

        // Gérer la case à cocher is_active
        $validated['is_active'] = $request->has('is_active');

        $food->update($validated);

        return redirect()->route('foods.index')
            ->with('success', 'Nourriture mise à jour avec succès.');
    }

    public function destroy(Food $food)
    {
        // Vérifier si la nourriture est utilisée dans des emplois du temps
        if ($food->schedules()->exists()) {
            return redirect()->route('foods.index')
                ->with('error', 'Impossible de supprimer cette nourriture car elle est utilisée dans des emplois du temps.');
        }

        $food->delete();

        return redirect()->route('foods.index')
            ->with('success', 'Nourriture supprimée avec succès.');
    }
}