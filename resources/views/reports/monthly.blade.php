<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport Mensuel - {{ $period['month'] }} {{ $period['year'] }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #1F2937;
            line-height: 1.5;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 10px;
            border-bottom: 1px solid #E5E7EB;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            color: #4F46E5;
            margin-bottom: 5px;
        }
        .subtitle {
            font-size: 16px;
            color: #6B7280;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #4F46E5;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 1px solid #E5E7EB;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background-color: #F3F4F6;
            text-align: left;
            padding: 8px;
            font-weight: bold;
            border-bottom: 1px solid #E5E7EB;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #E5E7EB;
        }
        .stats-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 20px;
        }
        .stat-box {
            background-color: #F9FAFB;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            padding: 15px;
            width: calc(33.333% - 15px);
            box-sizing: border-box;
        }
        .stat-title {
            font-size: 14px;
            color: #6B7280;
            margin-bottom: 5px;
        }
        .stat-value {
            font-size: 20px;
            font-weight: bold;
            color: #111827;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #6B7280;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Rapport Mensuel d'Élevage</div>
        <div class="subtitle">{{ $period['month'] }} {{ $period['year'] }} ({{ $period['start'] }} - {{ $period['end'] }})</div>
    </div>
    
    <!-- Résumé statistique -->
    <div class="section">
        <div class="section-title">Résumé du mois</div>
        <div class="stats-container">
            <div class="stat-box">
                <div class="stat-title">Naissances</div>
                <div class="stat-value">{{ $statistics['totalBirths'] }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-title">Décès</div>
                <div class="stat-value">{{ $statistics['totalDeaths'] }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-title">Ventes</div>
                <div class="stat-value">{{ $statistics['totalSales'] }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-title">Revenus</div>
                <div class="stat-value">{{ number_format($statistics['totalRevenue'], 2) }} F</div>
            </div>
            <div class="stat-box">
                <div class="stat-title">Taux de survie</div>
                <div class="stat-value">{{ $statistics['survivalRate'] }}%</div>
            </div>
            <div class="stat-box">
                <div class="stat-title">Poids moyen</div>
                <div class="stat-value">{{ number_format($statistics['averageWeight'], 2) }} kg</div>
            </div>
        </div>
    </div>
    
    <!-- Naissances -->
    <div class="section">
        <div class="section-title">Naissances ({{ $births->count() }} portées)</div>
        @if($births->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Mère</th>
                        <th>Nombre de lapereaux</th>
                        <th>Vivants</th>
                        <th>Morts</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($births as $breeding)
                        <tr>
                            <td>{{ $breeding->actual_birth_date->format('d/m/Y') }}</td>
                            <td>{{ $breeding->mother->name }}</td>
                            <td>{{ $breeding->number_of_kits }}</td>
                            <td>{{ $breeding->number_of_kits - ($breeding->number_of_deaths ?? 0) }}</td>
                            <td>{{ $breeding->number_of_deaths ?? 0 }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Aucune naissance enregistrée pour cette période.</p>
        @endif
    </div>
    
    <!-- Accouplements -->
    <div class="section">
        <div class="section-title">Accouplements ({{ $matings->count() }})</div>
        @if($matings->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Mère</th>
                        <th>Père</th>
                        <th>Naissance prévue</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($matings as $breeding)
                        <tr>
                            <td>{{ $breeding->mating_date->format('d/m/Y') }}</td>
                            <td>{{ $breeding->mother->name }}</td>
                            <td>{{ $breeding->father->name }}</td>
                            <td>{{ $breeding->expected_birth_date ? $breeding->expected_birth_date->format('d/m/Y') : 'Non définie' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Aucun accouplement enregistré pour cette période.</p>
        @endif
    </div>
    
    <div class="page-break"></div>
    
    <!-- Décès -->
    <div class="section">
        <div class="section-title">Décès ({{ $deaths->count() }})</div>
        @if($deaths->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Lapin</th>
                        <th>Âge</th>
                        <th>Cause</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($deaths as $rabbit)
                        <tr>
                            <td>{{ $rabbit->death_date->format('d/m/Y') }}</td>
                            <td>{{ $rabbit->name }}</td>
                            <td>{{ $rabbit->birth_date ? $rabbit->birth_date->diffForHumans($rabbit->death_date, true) : 'Inconnu' }}</td>
                            <td>{{ $rabbit->death_cause ?? 'Non spécifiée' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Aucun décès enregistré pour cette période.</p>
        @endif
    </div>
    
    <!-- Ventes -->
    <div class="section">
        <div class="section-title">Ventes ({{ $sales->count() }})</div>
        @if($sales->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Lapin</th>
                        <th>Prix</th>
                        <th>Acheteur</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sales as $rabbit)
                        <tr>
                            <td>{{ $rabbit->sold_at->format('d/m/Y') }}</td>
                            <td>{{ $rabbit->name }}</td>
                            <td>{{ number_format($rabbit->sale_price, 2) }} F</td>
                            <td>{{ $rabbit->buyer ?? 'Non spécifié' }}</td>
                        </tr>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Aucune vente enregistrée pour cette période.</p>
        @endif
    </div>
    
    <!-- Traitements -->
    <div class="section">
        <div class="section-title">Traitements ({{ $treatments->count() }})</div>
        @if($treatments->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Lapin</th>
                        <th>Médicament</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($treatments as $treatment)
                        <tr>
                            <td>{{ $treatment->scheduled_at->format('d/m/Y') }}</td>
                            <td>{{ $treatment->rabbit->name }}</td>
                            <td>{{ $treatment->medication->name }}</td>
                            <td>{{ $treatment->completed ? 'Effectué' : 'En attente' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Aucun traitement enregistré pour cette période.</p>
        @endif
    </div>
    
    <!-- Alimentation -->
    <div class="section">
        <div class="section-title">Alimentation ({{ $foodSchedules->count() }} distributions)</div>
        @if($foodSchedules->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Aliment</th>
                        <th>Quantité</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($foodSchedules as $schedule)
                        <tr>
                            <td>{{ $schedule->scheduled_at->format('d/m/Y') }}</td>
                            <td>{{ $schedule->food->name }}</td>
                            <td>{{ $schedule->quantity }} {{ $schedule->unit }}</td>
                            <td>{{ $schedule->completed ? 'Distribué' : 'En attente' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Aucune distribution d'aliments enregistrée pour cette période.</p>
        @endif
    </div>
    
    <div class="page-break"></div>
    
    <!-- Graphiques et analyses -->
    <div class="section">
        <div class="section-title">Analyses et tendances</div>
        
        <div style="margin-bottom: 20px;">
            <h3 style="font-size: 16px; margin-bottom: 10px;">Évolution du cheptel</h3>
            <p>Au cours de ce mois, le cheptel a {{ $statistics['totalBirths'] > $statistics['totalDeaths'] + $statistics['totalSales'] ? 'augmenté' : 'diminué' }} de {{ abs($statistics['totalBirths'] - ($statistics['totalDeaths'] + $statistics['totalSales'])) }} lapins.</p>
            <ul style="margin-left: 20px; list-style-type: disc;">
                <li>Entrées: {{ $statistics['totalBirths'] }} naissances</li>
                <li>Sorties: {{ $statistics['totalDeaths'] }} décès et {{ $statistics['totalSales'] }} ventes</li>
            </ul>
        </div>
        
        <div style="margin-bottom: 20px;">
            <h3 style="font-size: 16px; margin-bottom: 10px;">Santé et bien-être</h3>
            <p>Le taux de survie des lapereaux est de {{ $statistics['survivalRate'] }}%, ce qui est {{ $statistics['survivalRate'] > 85 ? 'excellent' : ($statistics['survivalRate'] > 70 ? 'bon' : 'à améliorer') }}.</p>
            <p>{{ $treatments->count() > 0 ? $treatments->where('completed', true)->count() . ' traitements ont été effectués sur ' . $treatments->count() . ' prévus.' : 'Aucun traitement n\'a été nécessaire ce mois-ci.' }}</p>
        </div>
        
        <div style="margin-bottom: 20px;">
            <h3 style="font-size: 16px; margin-bottom: 10px;">Performance économique</h3>
            <p>Revenus des ventes: {{ number_format($statistics['totalRevenue'], 2) }} F</p>
            <p>Prix de vente moyen: {{ $statistics['totalSales'] > 0 ? number_format($statistics['totalRevenue'] / $statistics['totalSales'], 2) : 0 }} F par lapin</p>
        </div>
    </div>
    
    <!-- Recommandations -->
    <div class="section">
        <div class="section-title">Recommandations</div>
        
        <ul style="margin-left: 20px; list-style-type: disc;">
            @if($statistics['survivalRate'] < 70)
                <li>Le taux de survie des lapereaux est bas. Vérifiez les conditions d'hébergement et l'alimentation des mères.</li>
            @endif
            
            @if($treatments->where('completed', false)->count() > 0)
                <li>{{ $treatments->where('completed', false)->count() }} traitements n'ont pas été effectués. Assurez-vous de suivre le calendrier médical.</li>
            @endif
            
            @if($matings->count() == 0)
                <li>Aucun accouplement n'a été enregistré ce mois-ci. Planifiez les prochaines reproductions.</li>
            @endif
            
            @if($statistics['totalSales'] > 0 && ($statistics['totalRevenue'] / $statistics['totalSales']) < 20)
                <li>Le prix de vente moyen est bas. Envisagez d'ajuster vos tarifs ou de cibler des marchés plus rentables.</li>
            @endif
        </ul>
    </div>
    
    <div class="footer">
        <p>Rapport généré le {{ now()->format('d/m/Y à H:i') }} | Gestion d'Élevage</p>
        <p>Ce document est confidentiel et destiné uniquement à l'usage interne.</p>
    </div>
</body>
</html>