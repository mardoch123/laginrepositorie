<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PushSubscriptionController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'endpoint' => 'required',
            'keys.auth' => 'required',
            'keys.p256dh' => 'required'
        ]);
        
        $request->user()->updatePushSubscription(
            $request->endpoint,
            $request->keys['p256dh'],
            $request->keys['auth'],
            $request->contentEncoding ?? 'aesgcm' // Valeur par défaut pour l'encodage
        );
        
        return response()->json(['success' => true]);
    }
    
    public function destroy(Request $request)
    {
        $request->user()->deletePushSubscription($request->endpoint);
        
        return response()->json(['success' => true]);
    }
}