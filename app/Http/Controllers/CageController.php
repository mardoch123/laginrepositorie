<?php

namespace App\Http\Controllers;

use App\Models\Cage;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cages = Cage::withCount('rabbits')->latest()->paginate(10);
        return view('cages.index', compact('cages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('cages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'location' => 'nullable|string|max:255',
                'is_active' => 'boolean',
            ]);
            
            $cage = Cage::create($validated);
            
            // Si c'est une requête AJAX, retourner une réponse JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'cage' => $cage,
                    'message' => 'Cage ajoutée avec succès'
                ]);
            }
            
            // Sinon, rediriger avec un message de succès
            return redirect()->route('cages.index')
                ->with('success', 'Cage ajoutée avec succès.');
        } catch (ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur de validation',
                    'errors' => $e->errors()
                ], 422);
            }
            
            throw $e;
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Une erreur est survenue lors de la création de la cage',
                    'error' => $e->getMessage()
                ], 500);
            }
            
            throw $e;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Cage $cage)
    {
        $cage->load('rabbits');
        return view('cages.show', compact('cage'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cage $cage)
    {
        return view('cages.edit', compact('cage'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cage $cage)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);
        
        $cage->update($validated);
        
        return redirect()->route('cages.index')
            ->with('success', 'Cage mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cage $cage)
    {
        // Vérifier si la cage contient des lapins
        if ($cage->rabbits()->count() > 0) {
            return redirect()->route('cages.index')
                ->with('error', 'Impossible de supprimer cette cage car elle contient des lapins.');
        }
        
        $cage->delete();
        
        return redirect()->route('cages.index')
            ->with('success', 'Cage supprimée avec succès.');
    }
}