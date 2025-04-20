<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FoodScheduleController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route pour vérifier l'état de santé des lapins
Route::get('/rabbit-health-check', [FoodScheduleController::class, 'checkRabbitHealth']);

## 7. Créer la route API
Route::post('/test-notification', 'App\Http\Controllers\NotificationController@testNotification')
    ->middleware('auth:sanctum');
