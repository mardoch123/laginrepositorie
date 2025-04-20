<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class FcmController extends Controller
{
    public function storeToken(Request $request)
    {
        $request->validate([
            'token' => 'required|string'
        ]);
        
        $user = auth()->user();
        $user->fcm_token = $request->token;
        $user->save();
        
        Log::info('Token FCM enregistré pour l\'utilisateur #' . $user->id);
        
        return response()->json(['success' => true, 'message' => 'Token enregistré avec succès']);
    }
    
    public function sendNotification(Request $request)
    {
        $user = auth()->user();
        
        // Pour les tests sur ordinateur, permettre l'envoi même sans token
        $testMode = $request->has('test_mode') || env('FCM_TEST_MODE', false);
        
        if (!$user->fcm_token && !$testMode) {
            Log::warning('Tentative d\'envoi de notification sans token FCM pour l\'utilisateur #' . $user->id);
            return response()->json(['success' => false, 'message' => 'Aucun token FCM trouvé pour cet utilisateur']);
        }
        
        // Si nous sommes en mode test, simuler une notification réussie
        if ($testMode) {
            Log::info('Envoi de notification FCM en mode TEST pour l\'utilisateur #' . $user->id);
            
            return response()->json([
                'success' => true, 
                'test_mode' => true,
                'message' => 'Notification simulée envoyée avec succès',
                'result' => [
                    'success' => 1,
                    'failure' => 0,
                    'test_mode' => true
                ]
            ]);
        }
        
        // Code normal pour envoyer une vraie notification
        $data = [
            'to' => $user->fcm_token,
            'notification' => [
                'title' => 'Test de notification',
                'body' => 'Ceci est un test de notification push via Firebase',
                'icon' => '/images/icon-192x192.png',
            ],
            'data' => [
                'url' => url('/dashboard')
            ]
        ];
        
        $serverKey = config('services.firebase.server_key');
        
        if (empty($serverKey)) {
            Log::error('Clé serveur Firebase non configurée');
            return response()->json(['success' => false, 'message' => 'Configuration Firebase incomplète']);
        }
        
        Log::info('Envoi de notification FCM à l\'utilisateur #' . $user->id);
        
        $url = 'https://fcm.googleapis.com/fcm/send';
        
        $headers = [
            'Authorization: key=' . $serverKey,
            'Content-Type: application/json'
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $result = curl_exec($ch);
        
        if (curl_errno($ch)) {
            Log::error('Erreur cURL: ' . curl_error($ch));
            return response()->json(['success' => false, 'message' => 'Erreur cURL: ' . curl_error($ch)]);
        }
        
        curl_close($ch);
        
        $resultData = json_decode($result, true);
        Log::info('Résultat FCM:', $resultData ?? ['error' => 'Réponse non JSON']);
        
        return response()->json(['success' => true, 'result' => $resultData]);
    }
}