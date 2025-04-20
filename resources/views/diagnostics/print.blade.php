<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnostic #{{ $diagnostic->id }} - Impression</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 20px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .diagnostic-id {
            font-size: 18px;
            color: #666;
        }
        .date {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
        }
        .section {
            margin-bottom: 30px;
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        .info-item {
            margin-bottom: 10px;
        }
        .info-label {
            font-weight: bold;
            color: #555;
        }
        .symptoms {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-top: 10px;
        }
        .diagnosis {
            background-color: #f5f5f5;
            padding: 20px;
            border-radius: 5px;
            margin-top: 20px;
            white-space: pre-line;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        @media print {
            body {
                padding: 0;
                margin: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">Gestion Élevage</div>
        <div class="diagnostic-id">Diagnostic #{{ $diagnostic->id }}</div>
        <div class="date">Date d'observation: {{ $diagnostic->observed_date->format('d/m/Y') }}</div>
        <div class="date">Date d'impression: {{ now()->format('d/m/Y H:i') }}</div>
    </div>

    <div class="section">
        <div class="section-title">Informations sur le lapin</div>
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Nom:</span> {{ $rabbit->name }}
            </div>
            <div class="info-item">
                <span class="info-label">ID:</span> {{ $rabbit->id }}
            </div>
            <div class="info-item">
                <span class="info-label">Race:</span> {{ $rabbit->breed }}
            </div>
            <div class="info-item">
                <span class="info-label">Sexe:</span> {{ $rabbit->gender == 'male' ? 'Mâle' : 'Femelle' }}
            </div>
            <div class="info-item">
                <span class="info-label">Âge:</span> {{ $rabbit->age }}
            </div>
            <div class="info-item">
                <span class="info-label">Poids:</span> {{ $diagnostic->weight ?? $rabbit->weight ?? 'Non spécifié' }} kg
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Détails du diagnostic</div>
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Température:</span> {{ $diagnostic->temperature ? $diagnostic->temperature . '°C' : 'Non spécifiée' }}
            </div>
            <div class="info-item">
                <span class="info-label">Appétit:</span> 
                @if($diagnostic->appetite_level == 'normal')
                    Normal
                @elseif($diagnostic->appetite_level == 'reduced')
                    Réduit
                @elseif($diagnostic->appetite_level == 'none')
                    Aucun
                @else
                    Non spécifié
                @endif
            </div>
            <div class="info-item">
                <span class="info-label">Activité:</span>
                @if($diagnostic->activity_level == 'normal')
                    Normale
                @elseif($diagnostic->activity_level == 'reduced')
                    Réduite
                @elseif($diagnostic->activity_level == 'lethargic')
                    Léthargique
                @else
                    Non spécifiée
                @endif
            </div>
        </div>
        
        <div class="info-item">
            <span class="info-label">Symptômes:</span>
            <div class="symptoms">{{ $diagnostic->symptoms }}</div>
        </div>
        
        @if($diagnostic->additional_notes)
        <div class="info-item">
            <span class="info-label">Notes supplémentaires:</span>
            <div class="symptoms">{{ $diagnostic->additional_notes }}</div>
        </div>
        @endif
    </div>

    @if($diagnostic->ai_diagnosis)
    <div class="section">
        <div class="section-title">Diagnostic assisté par IA</div>
        <div class="diagnosis">{{ $diagnostic->ai_diagnosis }}</div>
    </div>
    @endif

    @if($diagnostic->veterinarian_notes || $diagnostic->treatment_plan)
    <div class="section">
        <div class="section-title">Notes du vétérinaire</div>
        
        @if($diagnostic->veterinarian_notes)
        <div class="info-item">
            <span class="info-label">Observations:</span>
            <div class="symptoms">{{ $diagnostic->veterinarian_notes }}</div>
        </div>
        @endif
        
        @if($diagnostic->treatment_plan)
        <div class="info-item">
            <span class="info-label">Plan de traitement:</span>
            <div class="symptoms">{{ $diagnostic->treatment_plan }}</div>
        </div>
        @endif
        
        @if($diagnostic->follow_up_date)
        <div class="info-item">
            <span class="info-label">Date de suivi prévue:</span> {{ $diagnostic->follow_up_date->format('d/m/Y') }}
        </div>
        @endif
    </div>
    @endif

    <div class="footer">
        <p>Ce document est généré automatiquement par le système de gestion d'élevage.</p>
        <p>© {{ date('Y') }} Gestion Élevage - Tous droits réservés</p>
    </div>

    <div class="no-print" style="text-align: center; margin-top: 30px;">
        <button onclick="window.print()" style="padding: 10px 20px; background-color: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer;">
            Imprimer
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; background-color: #f44336; color: white; border: none; border-radius: 4px; cursor: pointer; margin-left: 10px;">
            Fermer
        </button>
    </div>
</body>
</html>