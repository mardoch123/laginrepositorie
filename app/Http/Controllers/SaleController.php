<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Rabbit;
use App\Models\Breeding;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    /**
     * Affiche la liste des ventes.
     */
    public function index(Request $request)
    {
        // Filtres
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : Carbon::now()->subMonths(1);
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : Carbon::now();
        $saleType = $request->input('sale_type');

        // Requête de base
        $query = Sale::with(['rabbit', 'breeding'])
                    ->whereBetween('sale_date', [$startDate, $endDate]);

        // Appliquer le filtre par type de vente si spécifié
        if ($saleType) {
            $query->where('sale_type', $saleType);
        }

        // Récupérer les ventes
        $sales = $query->orderBy('sale_date', 'desc')->get();

        // Statistiques
        $totalSales = $sales->count();
        $totalRevenue = $sales->sum('total_price');
        $totalWeight = $sales->sum('weight_kg');
        $averagePricePerKg = $totalWeight > 0 ? $totalRevenue / $totalWeight : 0;

        // Statistiques par mois (pour le graphique)
        $monthlySales = Sale::select(
            DB::raw('strftime("%Y", sale_date) as year'),
            DB::raw('strftime("%m", sale_date) as month'),
            DB::raw('SUM(total_price) as revenue'),
            DB::raw('SUM(weight_kg) as weight'),
            DB::raw('COUNT(*) as count')
        )
        ->whereBetween('sale_date', [$startDate->copy()->startOfMonth(), $endDate->copy()->endOfMonth()])
        ->groupBy('year', 'month')
        ->orderBy('year')
        ->orderBy('month')
        ->get();

        return view('sales.index', compact(
            'sales', 
            'totalSales', 
            'totalRevenue', 
            'totalWeight', 
            'averagePricePerKg', 
            'monthlySales',
            'startDate',
            'endDate',
            'saleType'
        ));
    }

    /**
     * Affiche le formulaire pour créer une nouvelle vente.
     */
    public function create()
    {
        // Récupérer tous les lapins individuels qui ne sont pas vendus ou morts
        $rabbitsForSale = Rabbit::where('status', '!=', 'sold')
            ->where('status', '!=', 'dead')
            ->where(function($query) {
                $query->whereNull('breeding_id')  // Lapins individuels (non liés à une portée)
                      ->orWhere('category', '!=', 'kit'); // Ou lapins qui ne sont pas des lapereaux
            })
            ->get();
        
        // Récupérer les portées disponibles pour la vente
        $breedingsForSale = Breeding::where(function($query) {
                $query->where('status', 'weaned')
                      ->orWhere('status', 'fattening')
                      ->orWhere('status', 'fattening_overdue')
                      ->orWhere('fattening_confirmed', true);
            })
            ->with(['mother', 'father'])
            ->get();
        
        return view('sales.create', compact('rabbitsForSale', 'breedingsForSale'));
    }

    /**
     * Enregistre une nouvelle vente.
     */
    public function store(Request $request)
    {
        // Validation de base pour tous les types de vente
        $rules = [
            'sale_type' => 'required|in:individual,group,breeding',
            'sale_date' => 'required|date',
            'weight_kg' => 'required|numeric|min:0.01',
            'price_per_kg' => 'required|numeric|min:0.01',
            'customer_name' => 'nullable|string|max:255',
            'customer_contact' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ];
        
        // Validation spécifique selon le type de vente
        if ($request->sale_type === 'individual') {
            $rules['rabbit_id'] = 'required|exists:rabbits,id';
        } elseif ($request->sale_type === 'group') {
            $rules['rabbit_ids'] = 'required|array|min:1';
            $rules['rabbit_ids.*'] = 'exists:rabbits,id';
            $rules['quantity'] = 'required|integer|min:1';
        } elseif ($request->sale_type === 'breeding') {
            $rules['breeding_id'] = 'required|exists:breedings,id';
        }
        
        try {
            // Valider les données
            $validated = $request->validate($rules);
            
            DB::beginTransaction();
            
            if ($request->sale_type === 'individual') {
                // Vente d'un lapin individuel
                $rabbit = Rabbit::findOrFail($request->rabbit_id);
                
                $sale = new Sale();
                $sale->rabbit_id = $rabbit->id;
                $sale->sale_type = 'individual';
                $sale->quantity = 1;
                $sale->weight_kg = $request->weight_kg;
                $sale->price_per_kg = $request->price_per_kg;
                $sale->total_price = $request->weight_kg * $request->price_per_kg;
                $sale->sale_date = $request->sale_date;
                $sale->customer_name = $request->customer_name;
                $sale->customer_contact = $request->customer_contact;
                $sale->notes = $request->notes;
                $sale->save();
                
                // Mettre à jour le statut du lapin
                $rabbit->status = 'sold';
                $rabbit->save();
            } 
            elseif ($request->sale_type === 'group') {
                // Vérifier que des lapins ont été sélectionnés
                if (empty($request->rabbit_ids)) {
                    return redirect()->back()->with('error', 'Veuillez sélectionner au moins un lapin pour la vente en groupe.')->withInput();
                }
                
                // Vente d'un groupe de lapins
                foreach ($request->rabbit_ids as $rabbitId) {
                    $rabbit = Rabbit::findOrFail($rabbitId);
                    
                    // Calculer le poids moyen par lapin
                    $avgWeight = $request->weight_kg / count($request->rabbit_ids);
                    
                    $sale = new Sale();
                    $sale->rabbit_id = $rabbit->id;
                    $sale->sale_type = 'group';
                    $sale->quantity = 1;
                    $sale->weight_kg = $avgWeight;
                    $sale->price_per_kg = $request->price_per_kg;
                    $sale->total_price = $avgWeight * $request->price_per_kg;
                    $sale->sale_date = $request->sale_date;
                    $sale->customer_name = $request->customer_name;
                    $sale->customer_contact = $request->customer_contact;
                    $sale->notes = $request->notes;
                    $sale->save();
                    
                    // Mettre à jour le statut du lapin
                    $rabbit->status = 'sold';
                    $rabbit->save();
                }
            } 
            elseif ($request->sale_type === 'breeding') {
                // Vente d'une portée entière
                $breeding = Breeding::findOrFail($request->breeding_id);
                
                $sale = new Sale();
                $sale->breeding_id = $breeding->id;
                $sale->sale_type = 'breeding';
                $sale->quantity = $breeding->number_of_kits;
                $sale->weight_kg = $request->weight_kg;
                $sale->price_per_kg = $request->price_per_kg;
                $sale->total_price = $request->weight_kg * $request->price_per_kg;
                $sale->sale_date = $request->sale_date;
                $sale->customer_name = $request->customer_name;
                $sale->customer_contact = $request->customer_contact;
                $sale->notes = $request->notes;
                $sale->save();
                
                // Mettre à jour le statut de la portée
                $breeding->fattening_confirmed = true;
                $breeding->status = 'completed';
                $breeding->save();
                
                // Mettre à jour le statut des lapereaux de cette portée
                Rabbit::where('breeding_id', $breeding->id)->update(['status' => 'sold']);
            }
            
            DB::commit();
            
            return redirect()->route('sales.index')->with('success', 'Vente enregistrée avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            // Amélioration du message d'erreur pour faciliter le débogage
            $errorMessage = 'Une erreur est survenue: ' . $e->getMessage();
            if (app()->environment('local')) {
                $errorMessage .= ' dans ' . $e->getFile() . ' à la ligne ' . $e->getLine();
            }
            return redirect()->back()->with('error', $errorMessage)->withInput();
        }
    }

    /**
     * Affiche les détails d'une vente.
     */
    public function show(Sale $sale)
    {
        return view('sales.show', compact('sale'));
    }

    /**
     * Affiche le formulaire pour modifier une vente.
     */
    public function edit(Sale $sale)
    {
        $rabbitsForSale = Rabbit::where(function($query) {
                $query->where('status', 'fattening')
                      ->orWhere(function($q) {
                          $q->where('category', 'kit')
                            ->whereNotNull('weaning_date')
                            ->where('status', 'active');
                      });
            })
            ->orWhere('id', $sale->rabbit_id)
            ->get();
        
        $breedingsForSale = Breeding::where(function($query) {
                $query->where('status', 'fattening_overdue')
                      ->orWhere('fattening_confirmed', true);
            })
            ->orWhere('id', $sale->breeding_id)
            ->with(['mother', 'father'])
            ->get();
        
        return view('sales.edit', compact('sale', 'rabbitsForSale', 'breedingsForSale'));
    }

    /**
     * Met à jour une vente.
     */
    public function update(Request $request, Sale $sale)
    {
        $validated = $request->validate([
            'sale_date' => 'required|date',
            'weight_kg' => 'required|numeric|min:0.01',
            'price_per_kg' => 'required|numeric|min:0.01',
            'customer_name' => 'nullable|string|max:255',
            'customer_contact' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        try {
            $sale->weight_kg = $request->weight_kg;
            $sale->price_per_kg = $request->price_per_kg;
            $sale->total_price = $request->weight_kg * $request->price_per_kg;
            $sale->sale_date = $request->sale_date;
            $sale->customer_name = $request->customer_name;
            $sale->customer_contact = $request->customer_contact;
            $sale->notes = $request->notes;
            $sale->save();
            
            return redirect()->route('sales.index')->with('success', 'Vente mise à jour avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Supprime une vente.
     */
    public function destroy(Sale $sale)
    {
        try {
            DB::beginTransaction();
            
            // Restaurer le statut du lapin ou de la portée
            if ($sale->rabbit_id) {
                $rabbit = Rabbit::find($sale->rabbit_id);
                if ($rabbit) {
                    $rabbit->status = 'fattening';
                    $rabbit->save();
                }
            } elseif ($sale->breeding_id) {
                $breeding = Breeding::find($sale->breeding_id);
                if ($breeding) {
                    $breeding->fattening_confirmed = false;
                    $breeding->status = 'fattening_overdue';
                    $breeding->save();
                    
                    // Restaurer le statut des lapereaux
                    Rabbit::where('breeding_id', $breeding->id)->update(['status' => 'fattening']);
                }
            }
            
            $sale->delete();
            
            DB::commit();
            
            return redirect()->route('sales.index')->with('success', 'Vente supprimée avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Une erreur est survenue: ' . $e->getMessage());
        }
    }

    /**
     * Affiche le rapport des ventes.
     */
    public function report(Request $request)
    {
        // Filtres
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : Carbon::now()->subMonths(3);
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : Carbon::now();
        
        // Statistiques générales
        $totalSales = Sale::whereBetween('sale_date', [$startDate, $endDate])->count();
        $totalRevenue = Sale::whereBetween('sale_date', [$startDate, $endDate])->sum('total_price');
        $totalWeight = Sale::whereBetween('sale_date', [$startDate, $endDate])->sum('weight_kg');
        $averagePricePerKg = $totalWeight > 0 ? $totalRevenue / $totalWeight : 0;
        
        // Ventes par type
        $salesByType = Sale::select('sale_type', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_price) as revenue'))
            ->whereBetween('sale_date', [$startDate, $endDate])
            ->groupBy('sale_type')
            ->get();
        
        
        
        // Pour les statistiques de vente par mois
        $salesByMonth = DB::table('sales')
            ->select(
                DB::raw('strftime("%Y", sale_date) as year'),
                DB::raw('strftime("%m", sale_date) as month'),
                DB::raw('SUM(total_price) as revenue'),
                DB::raw('SUM(weight_kg) as weight'),
                DB::raw('COUNT(*) as count')
            )
            ->whereBetween('sale_date', [$startDate, $endDate])
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();
        
        // Préparer les données pour le graphique
        $chartLabels = [];
        $chartRevenue = [];
        $chartWeight = [];
        
        foreach ($salesByMonth as $monthData) {
            $monthName = Carbon::createFromDate($monthData->year, $monthData->month, 1)->format('M Y');
            $chartLabels[] = $monthName;
            $chartRevenue[] = $monthData->revenue;
            $chartWeight[] = $monthData->weight;
        }
        
        return view('sales.report', compact(
            'totalSales', 
            'totalRevenue', 
            'totalWeight', 
            'averagePricePerKg',
            'salesByType',
            'salesByMonth',
            'chartLabels',
            'chartRevenue',
            'chartWeight',
            'startDate',
            'endDate'
        ));
    }
}