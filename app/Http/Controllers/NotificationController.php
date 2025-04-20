<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use App\Notifications\ReminderNotification;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\TestNotification;

class NotificationController extends Controller
{
    public function testNotification(Request $request)
    {
        $user = auth()->user();
        
        // Récupérer un rappel existant ou créer un rappel de test temporaire
        $reminder = Reminder::find(2);
        
        if (!$reminder) {
            // Créer un rappel de test si aucun n'existe
            $reminder = new Reminder([
                'title' => 'Test de notification',
                'description' => 'Ceci est un test de notification push',
                'priority' => 'high',
                'active' => true
            ]);
        }
        
        try {
            $user->notify(new ReminderNotification($reminder));
            
            // Si la requête attend du JSON, retourner du JSON
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Notification de test envoyée avec succès!'
                ]);
            }
            
            // Sinon, rediriger avec un message flash
            return back()->with('success', 'Notification de test envoyée. Vérifiez votre navigateur.');
        } catch (\Exception $e) {
            // Si la requête attend du JSON, retourner du JSON
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de l\'envoi de la notification: ' . $e->getMessage()
                ], 500);
            }
            
            // Sinon, rediriger avec un message flash
            return back()->with('error', 'Erreur lors de l\'envoi de la notification: ' . $e->getMessage());
        }
    }
}