<?php

use App\Http\Controllers\AnimalTypeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\OfflineController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RabbitController;
use App\Http\Controllers\CageController;
use App\Http\Controllers\BreedingController;
use App\Http\Controllers\KitController;
use App\Http\Controllers\MedicationController;
use App\Http\Controllers\TreatmentController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\FoodScheduleController;
use App\Http\Controllers\PushSubscriptionController;
use App\Http\Controllers\ProtocolController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\FcmController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\HealthController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\DiagnosticController;


// Welcome page route
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');
});

// Routes pour la gestion des rôles
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
    Route::get('/users/manage', [RoleController::class, 'manageUsers'])->name('users.manage');
    Route::put('/users/{user}/role', [RoleController::class, 'updateUserRole'])->name('users.update.role');
});

// Routes pour la synchronisation hors ligne
Route::middleware(['auth'])->group(function () {
    Route::get('/offline/sync', [OfflineController::class, 'syncPage'])->name('offline.sync');
    Route::post('/offline/upload', [OfflineController::class, 'uploadData'])->name('offline.upload');
    Route::get('/offline/download', [OfflineController::class, 'downloadData'])->name('offline.download');
    Route::get('/offline/app', [OfflineController::class, 'offlineApp'])->name('offline.app');
    
    // Nouvelles routes pour les pages hors ligne
    Route::get('/offline/rabbits', [OfflineController::class, 'offlineRabbits'])->name('offline.rabbits');
    Route::get('/offline/cages', [OfflineController::class, 'offlineCages'])->name('offline.cages');
    Route::get('/offline/treatments', [OfflineController::class, 'offlineTreatments'])->name('offline.treatments');
    Route::get('/offline/breedings', [OfflineController::class, 'offlineBreedings'])->name('offline.breedings');
});

// Routes accessibles aux gestionnaires de ferme (lecture et création seulement)
Route::middleware(['auth', 'role:admin,farm_manager'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // Routes en lecture seule pour les gestionnaires
    Route::get('rabbits', [RabbitController::class, 'index'])->name('rabbits.index');
    Route::get('rabbits/create', [RabbitController::class, 'create'])->name('rabbits.create');
    Route::post('rabbits', [RabbitController::class, 'store'])->name('rabbits.store');
    Route::get('rabbits/{rabbit}', [RabbitController::class, 'show'])->name('rabbits.show');
    
    Route::get('cages', [CageController::class, 'index'])->name('cages.index');
    Route::get('cages/create', [CageController::class, 'create'])->name('cages.create');
    Route::post('cages', [CageController::class, 'store'])->name('cages.store');
    Route::get('cages/{cage}', [CageController::class, 'show'])->name('cages.show');
    
    // ... autres routes en lecture seule pour les gestionnaires ...
    
    // Routes pour les notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    
    // Routes pour les traitements à venir
    Route::get('/treatments/upcoming', [TreatmentController::class, 'upcoming'])->name('treatments.upcoming');
});

// Routes réservées aux administrateurs (modification et suppression)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('rabbits/{rabbit}/edit', [RabbitController::class, 'edit'])->name('rabbits.edit');
    Route::put('rabbits/{rabbit}', [RabbitController::class, 'update'])->name('rabbits.update');
    Route::delete('rabbits/{rabbit}', [RabbitController::class, 'destroy'])->name('rabbits.destroy');
    
    Route::get('cages/{cage}/edit', [CageController::class, 'edit'])->name('cages.edit');
    Route::put('cages/{cage}', [CageController::class, 'update'])->name('cages.update');
    Route::delete('cages/{cage}', [CageController::class, 'destroy'])->name('cages.destroy');
    
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // ... autres routes de modification et suppression ...
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Routes pour la gestion du type d'animal
    Route::post('/set-animal-type', [AnimalTypeController::class, 'setAnimalType'])->name('set.animal.type');
    Route::get('/custom-animal-type', [AnimalTypeController::class, 'showCustomForm'])->name('animal.type.custom');
    Route::post('/custom-animal-type', [AnimalTypeController::class, 'setCustomAnimalType'])->name('animal.type.custom.store');
    // À l'intérieur du groupe middleware auth
    Route::resource('rabbits', RabbitController::class);
    Route::resource('cages', CageController::class);
    // Ajouter cette route dans le groupe middleware auth
    Route::post('/rabbits/name-suggestions', [App\Http\Controllers\RabbitController::class, 'nameSuggestions'])->name('rabbits.name-suggestions');
    // Ajouter ces routes à votre fichier web.php existant
    Route::resource('reminders', ReminderController::class);
    Route::post('reminders/{reminder}/toggle', [ReminderController::class, 'toggleActive'])->name('reminders.toggle');
    Route::get('reminders/{reminder}/logs', [ReminderController::class, 'logs'])->name('reminders.logs');
    
    // Routes pour les protocoles
    Route::get('/protocols', [App\Http\Controllers\ProtocolController::class, 'index'])->name('protocols.index');
    Route::get('/protocols/create', [App\Http\Controllers\ProtocolController::class, 'create'])->name('protocols.create');
    Route::post('/protocols', [App\Http\Controllers\ProtocolController::class, 'store'])->name('protocols.store');
    Route::get('/protocols/{name}', [App\Http\Controllers\ProtocolController::class, 'show'])->name('protocols.show');
    Route::post('/protocols/{id}/complete', [App\Http\Controllers\ProtocolController::class, 'complete'])->name('protocols.complete');
    Route::post('/protocols/{id}/cancel', [App\Http\Controllers\ProtocolController::class, 'cancel'])->name('protocols.cancel');
    
    Route::resource('breedings', BreedingController::class);
    Route::get('breedings-calendar', [BreedingController::class, 'calendar'])->name('breedings.calendar');
    
    // Routes pour les lapereaux
    Route::get('/kits', [App\Http\Controllers\KitController::class, 'index'])->name('kits.index');
    // Routes pour l'engraissement
    Route::get('/kits/fattening', [App\Http\Controllers\KitController::class, 'fattening'])->name('kits.fattening');
    Route::post('/kits/start-fattening', [App\Http\Controllers\KitController::class, 'startFattening'])->name('kits.start-fattening');
    
    // Routes pour les médicaments et traitements
    Route::resource('medications', MedicationController::class);
    Route::resource('treatments', TreatmentController::class);
    Route::get('treatments-calendar', [TreatmentController::class, 'calendar'])->name('treatments.calendar');
    Route::get('treatments-calendar/events', [TreatmentController::class, 'getCalendarEvents'])->name('treatments.calendar.events');
    // Modifier ces routes pour accepter les requêtes GET et POST
    Route::match(['get', 'post'], '/treatments/{treatment}/done', [TreatmentController::class, 'markAsDone'])->name('treatments.done');
    Route::match(['get', 'post'], '/treatments/{treatment}/skip', [TreatmentController::class, 'markAsSkipped'])->name('treatments.skip');
    Route::get('rabbits/{rabbit}/treatments', [TreatmentController::class, 'history'])->name('rabbits.treatments');
    
    // Routes pour les nourritures
    Route::resource('foods', FoodController::class);
    
    // Routes pour les emplois du temps de nourriture
    Route::get('/food-schedules', [FoodScheduleController::class, 'index'])->name('food-schedules.index');
    Route::post('/food-schedules/{foodSchedule}/complete', [FoodScheduleController::class, 'markAsCompleted'])->name('food-schedules.complete');
    // Dans le groupe de routes existant
    Route::post('/food-schedules/generate', [FoodScheduleController::class, 'generateSchedules'])->name('food-schedules.generate');
    Route::post('/food-schedules/generate', [FoodScheduleController::class, 'generateManually'])->name('food-schedules.generate');
    // Routes pour les notifications push
    // Dans le groupe middleware auth
    Route::post('/push-subscriptions', [PushSubscriptionController::class, 'store'])->name('push-subscriptions.store');
    Route::delete('/push-subscriptions', [PushSubscriptionController::class, 'destroy'])->name('push-subscriptions.destroy');
    Route::match(['get', 'post'], '/test-notification', [App\Http\Controllers\NotificationController::class, 'testNotification'])
        ->name('test.notification');
    // Routes pour Firebase Cloud Messaging
    Route::post('/fcm-token', [App\Http\Controllers\FcmController::class, 'storeToken'])
        ->middleware(['auth'])
        ->name('fcm.store');
    
    Route::post('/send-fcm-notification', [App\Http\Controllers\FcmController::class, 'sendNotification'])
        ->middleware(['auth'])
        ->name('fcm.send');
});

require __DIR__.'/auth.php';


// Routes pour les dépenses
Route::middleware(['auth'])->group(function () {
    Route::resource('expenses', 'App\Http\Controllers\ExpenseController');
    Route::get('expenses/category/{category}', 'App\Http\Controllers\ExpenseController@byCategory')->name('expenses.by-category');
    Route::get('expenses/period/{period}', 'App\Http\Controllers\ExpenseController@byPeriod')->name('expenses.by-period');
});

// Routes pour les rapports
Route::middleware(['auth'])->group(function () {
    Route::get('reports', 'App\Http\Controllers\ReportController@index')->name('reports.index');
    Route::get('reports/breeding', 'App\Http\Controllers\ReportController@breeding')->name('reports.breeding');
    Route::get('reports/financial', 'App\Http\Controllers\ReportController@financial')->name('reports.financial');
    Route::get('reports/health', 'App\Http\Controllers\ReportController@health')->name('reports.health');
    Route::get('reports/generate/{type}', 'App\Http\Controllers\ReportController@generate')->name('reports.generate');
    Route::post('reports/export', 'App\Http\Controllers\ReportController@export')->name('reports.export');
});
Route::get('/reports/monthly', [ReportController::class, 'generateMonthly'])->name('reports.generate-monthly');
Route::get('/reports/treatments', [ReportController::class, 'generateTreatments'])->name('reports.generate-treatments');


// Routes pour les signalements de santé
Route::middleware(['auth'])->group(function () {
    // Signalements de mortalité
    Route::get('health/mortality', 'App\Http\Controllers\HealthController@mortalityIndex')->name('health.mortality.index');
    Route::get('health/mortality/create', 'App\Http\Controllers\HealthController@mortalityCreate')->name('health.mortality.create');
    Route::post('health/mortality', 'App\Http\Controllers\HealthController@mortalityStore')->name('health.mortality.store');
    
    // Signalements de maladie
    Route::get('health/illness', 'App\Http\Controllers\HealthController@illnessIndex')->name('health.illness.index');
    Route::get('health/illness/create', 'App\Http\Controllers\HealthController@illnessCreate')->name('health.illness.create');
    Route::post('health/illness', 'App\Http\Controllers\HealthController@illnessStore')->name('health.illness.store');
    Route::get('health/illness/{illness}/edit', 'App\Http\Controllers\HealthController@illnessEdit')->name('health.illness.edit');
    Route::put('health/illness/{illness}', 'App\Http\Controllers\HealthController@illnessUpdate')->name('health.illness.update');
    Route::delete('health/illness/{illness}', 'App\Http\Controllers\HealthController@illnessDestroy')->name('health.illness.destroy');
    Route::get('health/illness/{illness}', [HealthController::class, 'illnessShow'])->name('health.illness.show');
    
    // Tableau de bord de santé
    Route::get('health/dashboard', 'App\Http\Controllers\HealthController@dashboard')->name('health.dashboard');
});

// Route for recording rabbit weight
Route::post('/rabbits/record-weight', [App\Http\Controllers\RabbitController::class, 'recordWeight'])->name('rabbits.record-weight');


// Routes pour les ventes
Route::get('/sales', [App\Http\Controllers\SaleController::class, 'index'])->name('sales.index');
Route::get('/sales/create', [App\Http\Controllers\SaleController::class, 'create'])->name('sales.create');
Route::post('/sales', [App\Http\Controllers\SaleController::class, 'store'])->name('sales.store');
Route::get('/sales/{sale}', [App\Http\Controllers\SaleController::class, 'show'])->name('sales.show');
Route::get('/sales/{sale}/edit', [App\Http\Controllers\SaleController::class, 'edit'])->name('sales.edit');
Route::put('/sales/{sale}', [App\Http\Controllers\SaleController::class, 'update'])->name('sales.update');
Route::delete('/sales/{sale}', [App\Http\Controllers\SaleController::class, 'destroy'])->name('sales.destroy');
Route::get('/sales-report', [App\Http\Controllers\SaleController::class, 'report'])->name('sales.report');


Route::post('/kits/assign-cage', [App\Http\Controllers\KitController::class, 'assignCage'])->name('kits.assign-cage');

// Optimization routes
Route::get('/optimization', [App\Http\Controllers\BreedingOptimizationController::class, 'index'])->name('optimization.index');
Route::post('/optimization/simulate', [App\Http\Controllers\BreedingOptimizationController::class, 'simulateProduction'])->name('optimization.simulate');

// Routes pour les diagnostics IA
// Assurez-vous que vos routes diagnostics sont dans un groupe avec middleware auth
Route::middleware(['auth'])->group(function () {
    // Routes pour les diagnostics
    Route::resource('diagnostics', DiagnosticController::class);
    Route::get('/diagnostics/{diagnostic}/print', [DiagnosticController::class, 'print'])->name('diagnostics.print');
    // Modifier cette ligne pour utiliser POST au lieu de DELETE
    Route::post('/diagnostics/bulk-delete', [DiagnosticController::class, 'bulkDelete'])->name('diagnostics.bulk-delete');
});


Route::get('/offline', function () {
    return view('offline');
})->name('offline');
Route::get('/treatments/upcoming', [TreatmentController::class, 'upcoming'])->name('treatments.upcoming');
