<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reçu de Paiement - {{ $paiement->numero_facture }}</title>
    <style>
        :root {
            --primary: #2563eb;
            --text: #1f2937;
            --text-muted: #6b7280;
            --border: #e5e7eb;
            --light: #f9fafb;
            --green: #10b981;
            --red: #ef4444;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text);
            line-height: 1.5;
            margin: 0;
            padding: 40px;
            background: #fff;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid var(--border);
            padding: 40px;
            border-radius: 8px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid var(--primary);
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 28px;
            font-weight: 800;
            color: var(--primary);
            margin: 0 0 5px 0;
        }
        .boutique-info {
            font-size: 13.5px;
            color: var(--text-muted);
        }
        .title-block {
            text-align: right;
        }
        .doc-title {
            font-size: 22px;
            font-weight: 800;
            color: var(--text);
            margin: 0 0 5px 0;
        }
        .doc-number {
            font-size: 15px;
            font-weight: 600;
            color: var(--primary);
        }
        .grid-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }
        .detail-block h3 {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border);
            padding-bottom: 6px;
            margin: 0 0 10px 0;
        }
        .detail-block p {
            margin: 3px 0;
            font-size: 14.5px;
        }
        .payment-box {
            background: var(--light);
            border: 1px solid var(--border);
            border-radius: 6px;
            padding: 24px;
            margin-bottom: 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .payment-amount-label {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-muted);
            margin: 0;
        }
        .payment-amount {
            font-size: 32px;
            font-weight: 800;
            color: var(--green);
            margin: 0;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }
        .summary-table th, .summary-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }
        .summary-table th {
            font-size: 13px;
            text-transform: uppercase;
            color: var(--text-muted);
            background: var(--light);
        }
        .summary-table td {
            font-size: 14.5px;
        }
        .text-right {
            text-align: right;
        }
        .footer {
            text-align: center;
            font-size: 13px;
            color: var(--text-muted);
            margin-top: 60px;
            border-top: 1px solid var(--border);
            padding-top: 20px;
        }
        .action-bar {
            max-width: 800px;
            margin: 20px auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .btn {
            padding: 10px 20px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-secondary {
            background: #fff;
            border: 1px solid var(--border);
            color: var(--text);
        }
        .btn-primary {
            background: var(--primary);
            color: #fff;
        }
        @media print {
            body {
                padding: 0;
            }
            .container {
                border: none;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <div class="action-bar no-print">
        <a href="javascript:window.close();" class="btn btn-secondary" id="closeBtn">
            ← Fermer
        </a>
        <button onclick="window.print();" class="btn btn-primary">
            🖨️ Imprimer le reçu
        </button>
    </div>

    <div class="container">
        <div class="header">
            <div>
                <h1 class="logo">{{ $paiement->commande->boutique->nom }}</h1>
                <div class="boutique-info">
                    @if($paiement->commande->boutique->adresse)
                        <p style="margin: 2px 0;">📍 {{ $paiement->commande->boutique->adresse }}</p>
                    @endif
                    @if($paiement->commande->boutique->telephone)
                        <p style="margin: 2px 0;">📞 {{ $paiement->commande->boutique->telephone }}</p>
                    @endif
                </div>
            </div>
            <div class="title-block">
                <h2 class="doc-title">REÇU DE PAIEMENT</h2>
                <div class="doc-number">{{ $paiement->numero_facture }}</div>
                <div class="boutique-info" style="margin-top: 5px;">
                    Date : {{ $paiement->created_at->format('d/m/Y H:i') }}
                </div>
            </div>
        </div>

        <div class="grid-details">
            <div class="detail-block">
                <h3>Client</h3>
                <p><strong>{{ $paiement->commande->client->nom_complet ?? 'Client Anonyme' }}</strong></p>
                @if($paiement->commande->client)
                    @if($paiement->commande->client->telephone)
                        <p>📞 {{ $paiement->commande->client->telephone }}</p>
                    @endif
                    @if($paiement->commande->client->adresse)
                        <p>📍 {{ $paiement->commande->client->adresse }}</p>
                    @endif
                @endif
            </div>
            <div class="detail-block">
                <h3>Détails du versement</h3>
                <p>Mode de règlement : 
                    <strong>
                        @if($paiement->mode === 'especes')
                            Espèces
                        @elseif($paiement->mode === 'mobile_money')
                            Mobile Money
                        @elseif($paiement->mode === 'cheque')
                            Chèque
                        @elseif($paiement->mode === 'virement')
                            Virement
                        @else
                            {{ ucfirst($paiement->mode) }}
                        @endif
                    </strong>
                </p>
                <p>Vente de référence : <strong>{{ $paiement->commande->numero }}</strong></p>
                <p>Date de la vente : {{ $paiement->commande->created_at->format('d/m/Y') }}</p>
            </div>
        </div>

        <div class="payment-box">
            <div>
                <p class="payment-amount-label">MONTANT REÇU</p>
                <p style="font-size: 12px; color: var(--text-muted); margin: 2px 0 0 0;">Règlement partiel ou total de la vente</p>
            </div>
            <p class="payment-amount">{{ number_format($paiement->montant, 0, ',', ' ') }} F CFA</p>
        </div>

        <div class="detail-block">
            <h3>Situation de la vente</h3>
        </div>
        
        <table class="summary-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="text-right">Montant</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Total de la vente (TTC)</td>
                    <td class="text-right">{{ number_format($paiement->commande->total_ttc, 0, ',', ' ') }} F</td>
                </tr>
                @php
                    $paiementsDeLaVente = $paiement->commande->paiements()->orderBy('id', 'asc')->get();
                    $cumulPaye = 0;
                    foreach ($paiementsDeLaVente as $p) {
                        $cumulPaye += $p->montant;
                        if ($p->id === $paiement->id) {
                            break;
                        }
                    }
                    $reste = $paiement->commande->total_ttc - $cumulPaye;
                @endphp
                <tr>
                    <td>Cumul payé (inclus ce versement)</td>
                    <td class="text-right" style="color: var(--green); font-weight: 600;">{{ number_format($cumulPaye, 0, ',', ' ') }} F</td>
                </tr>
                <tr>
                    <td><strong>Reste à payer (Dette actuelle)</strong></td>
                    <td class="text-right" style="font-weight: 700; color: {{ $reste > 0 ? 'var(--red)' : 'var(--text)' }};">
                        {{ number_format($reste, 0, ',', ' ') }} F
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="footer">
            <p>Merci pour votre confiance !</p>
            <p style="font-size: 11px; color: var(--text-muted);">Généré automatiquement par l'application MiabéStock</p>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>
