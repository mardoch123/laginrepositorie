<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AnimalTypeController extends Controller
{
    public function setAnimalType(Request $request)
    {
        $animalType = $request->input('animal_type');
        
        // Définir les valeurs en session
        switch ($animalType) {
            case 'rabbit':
                session(['animal_type' => 'Lapins', 'animal_type_singular' => 'Lapin']);
                break;
            case 'chicken':
                session(['animal_type' => 'Volailles', 'animal_type_singular' => 'Volaille']);
                break;
            case 'goat':
                session(['animal_type' => 'Chèvres', 'animal_type_singular' => 'Chèvre']);
                break;
            case 'other':
                // Rediriger vers un formulaire pour spécifier le type d'animal
                return redirect()->route('animal.type.custom');
            default:
                session(['animal_type' => 'Animaux', 'animal_type_singular' => 'Animal']);
        }
        
        return redirect()->route('dashboard');
    }
    
    public function showCustomForm()
    {
        return view('animal-type-custom');
    }
    
    public function setCustomAnimalType(Request $request)
    {
        $request->validate([
            'animal_type_plural' => 'required|string|max:50',
            'animal_type_singular' => 'required|string|max:50',
        ]);
        
        session([
            'animal_type' => $request->animal_type_plural,
            'animal_type_singular' => $request->animal_type_singular
        ]);
        
        return redirect()->route('dashboard');
    }
}