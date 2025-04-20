<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport de Traitements - {{ $period['start'] }} au {{ $period['end'] }}</title>
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
            color: #059669;
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
            color: #059669;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 1px solid #E5E7EB;
        }
        .subsection-title {
            font-size: 16px;
            font-weight: bold;
            color: #1F2937;
            margin: 15px 0 10px 0;
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
        .completed {
            color: #059669;
            font-weight: bold;
        }
        .pending {
            color: #D97706;
            font-weight: bold;
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
        .rabbit-section {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #F9FAFB;
            border-radius: 8px;
            border: 1px solid #E5E7EB;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Rapport de Traitements Médicaux</div>
        <div class="subtitle">Période du {{ $period['start'] }} au {{ $period['end'] }}</div>
    </div>
    
    <!-- Résumé statistique -->
    <div class="section">
        <div class="section-title">Résumé des traitements</div>
        <div class="stats-container">
            <div class="stat-box">
                <div class="stat-title">Total des traitements</div>
                <div class="stat-value">{{ $statistics['totalTreatments'] }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-title">Traitements effectués</div>
                <div class="stat-value completed">{{ $statistics['completedTreatments'] }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-title">Traitements en attente</div>
                <div class="stat-value pending">{{ $statistics['pendingTreatments'] }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-title">Taux de réalisation</div>
                <div class="stat-value">{{ $statistics['totalTreatments'] > 0 ? number_format(($statistics['completedTreatments'] / $statistics['totalTreatments']) * 100, 1) : 0 }}%</div>
            </div>
            <div class="stat-box">
                <div class="stat-title">Lapins traités</div>
                <div class="stat-value">{{ $treatmentsByRabbit->count() }}</div>
            </div>
        </div>
    </div>
    
    <!-- Liste chronologique des traitements -->
    <div class="section">
        <div class="section-title">Chronologie des traitements</div>
        @if($treatments->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Lapin</th>
                        <th>Médicament</th>
                        <th>Dosage</th>
                        <th>Statut</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($treatments as $treatment)
                        <tr>
                            <td>{{ $treatment->scheduled_at->format('d/m/Y') }}</td>
                            <td>{{ $treatment->rabbit->name }}</td>
                            <td>{{ $treatment->medication->name }}</td>
                            <td>{{ $treatment->dosage }} {{ $treatment->unit }}</td>
                            <td class="{{ $treatment->completed ? 'completed' : 'pending' }}">
                                {{ $treatment->completed ? 'Effectué' : 'En attente' }}
                            </td>
                            <td>{{ $treatment->notes ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Aucun traitement enregistré pour cette période.</p>
        @endif
    </div>
    
    <div class="page-break"></div>
    
    <!-- Traitements par lapin -->
    <div class="section">
        <div class="section-title">Traitements par lapin</div>
        
        @if($treatmentsByRabbit->count() > 0)
            @foreach($treatmentsByRabbit as $rabbitId => $rabbitTreatments)
                <div class="rabbit-section">
                    <div class="subsection-title">{{ $rabbitTreatments->first()->rabbit->name }}</div>
                    <p>
                        <strong>Race:</strong> {{ $rabbitTreatments->first()->rabbit->breed ?? 'Non spécifiée' }} |
                        <strong>Sexe:</strong> {{ $rabbitTreatments->first()->rabbit->gender == 'male' ? 'Mâle' : 'Femelle' }} |
                        <strong>Âge:</strong> {{ $rabbitTreatments->first()->rabbit->birth_date ? $rabbitTreatments->first()->rabbit->birth_date->diffForHumans(now(), true) : 'Inconnu' }}
                    </p>
                    
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Médicament</th>
                                <th>Dosage</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rabbitTreatments as $treatment)
                                <tr>
                                    <td>{{ $treatment->scheduled_at->format('d/m/Y') }}</td>
                                    <td>{{ $treatment->medication->name }}</td>
                                    <td>{{ $treatment->dosage }} {{ $treatment->unit }}</td>
                                    <td class="{{ $treatment->completed ? 'completed' : 'pending' }}">
                                        {{ $treatment->completed ? 'Effectué' : 'En attente' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    <p><strong>Notes médicales:</strong> {{ $rabbitTreatments->first()->rabbit->medical_notes ?? 'Aucune note médicale' }}</p>
                </div>
            @endforeach
        @else
            <p>Aucun traitement enregistré pour cette période.</p>
        @endif
    </div>
    
    <!-- Recommandations -->
    <div class="section">
        <div class="section-title">Recommandations</div>
        
        <ul style="margin-left: 20px; list-style-type: disc;">
            @if($statistics['pendingTreatments'] > 0)
                <li>{{ $statistics['pendingTreatments'] }} traitements sont encore en attente. Assurez-vous de les effectuer selon le calendrier prévu.</li>
            @endif
            
            @if($treatments->where('completed', true)->count() > 5)
                <li>Un nombre important de traitements a été nécessaire durant cette période. Envisagez une révision des conditions d'élevage pour améliorer la santé globale.</li>
            @endif
            
            @if($treatmentsByRabbit->count() > 0)
                @foreach($treatmentsByRabbit as $rabbitId => $rabbitTreatments)
                    @if($rabbitTreatments->count() > 2)
                        <li>{{ $rabbitTreatments->first()->rabbit->name }} a reçu {{ $rabbitTreatments->count() }} traitements. Un suivi vétérinaire spécifique pourrait être nécessaire.</li>
                    @endif
                @endforeach
            @endif
        </ul>
    </div>
    
    <div class="footer">
        <p>Rapport généré le {{ now()->format('d/m/Y à H:i') }} | Gestion d'Élevage</p>
        <p>Ce document est confidentiel et destiné uniquement à l'usage interne.</p>
    </div>
</body>
</html>