<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture - {{ $vente->numero }}</title>
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
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .invoice-table th, .invoice-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }
        .invoice-table th {
            font-size: 13px;
            text-transform: uppercase;
            color: var(--text-muted);
            background: var(--light);
        }
        .invoice-table td {
            font-size: 14.5px;
        }
        .text-right {
            text-align: right;
        }
        .totals-section {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 40px;
        }
        .totals-table {
            width: 320px;
            border-collapse: collapse;
        }
        .totals-table td {
            padding: 8px 15px;
            font-size: 14.5px;
            border-bottom: 1px solid var(--border);
        }
        .totals-table tr.total-row td {
            font-size: 18px;
            font-weight: 800;
            color: var(--text);
            border-bottom: 2px solid var(--primary);
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
            🖨️ Imprimer la facture
        </button>
    </div>

    <div class="container">
        <div class="header">
            <div>
                <h1 class="logo">{{ $vente->boutique->nom }}</h1>
                <div class="boutique-info">
                    @if($vente->boutique->adresse)
                        <p style="margin: 2px 0;">📍 {{ $vente->boutique->adresse }}</p>
                    @endif
                    @if($vente->boutique->telephone)
                        <p style="margin: 2px 0;">📞 {{ $vente->boutique->telephone }}</p>
                    @endif
                </div>
            </div>
            <div class="title-block">
                <h2 class="doc-title">FACTURE</h2>
                <div class="doc-number">{{ $vente->numero }}</div>
                <div class="boutique-info" style="margin-top: 5px;">
                    Date : {{ $vente->created_at->format('d/m/Y H:i') }}
                </div>
            </div>
        </div>

        <div class="grid-details">
            <div class="detail-block">
                <h3>Client</h3>
                <p><strong>{{ $vente->client->nom_complet ?? 'Client Anonyme' }}</strong></p>
                @if($vente->client)
                    @if($vente->client->telephone)
                        <p>📞 {{ $vente->client->telephone }}</p>
                    @endif
                    @if($vente->client->adresse)
                        <p>📍 {{ $vente->client->adresse }}</p>
                    @endif
                @endif
            </div>
            <div class="detail-block">
                <h3>Informations</h3>
                <p>Statut de paiement : 
                    <strong>
                        @if($vente->statut === 'soldee')
                            Payée intégralement
                        @elseif($vente->statut === 'annulee')
                            Annulée
                        @else
                            Paiement partiel
                        @endif
                    </strong>
                </p>
                <p>Vendeur : <strong>{{ $vente->user->name ?? 'Système' }}</strong></p>
            </div>
        </div>

        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Description du produit</th>
                    <th class="text-right">Qté</th>
                    <th class="text-right">Prix Unitaire</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vente->lignes as $ligne)
                    <tr>
                        <td>{{ $ligne->produit->nom ?? 'Produit inconnu' }}</td>
                        <td class="text-right">{{ $ligne->quantite }}</td>
                        <td class="text-right">{{ number_format($ligne->prix_unitaire, 0, ',', ' ') }} F</td>
                        <td class="text-right font-medium">{{ number_format($ligne->sous_total, 0, ',', ' ') }} F</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals-section">
            <table class="totals-table">
                <tr class="total-row">
                    <td><strong>Total TTC</strong></td>
                    <td class="text-right"><strong>{{ number_format($vente->total_ttc, 0, ',', ' ') }} F</strong></td>
                </tr>
                <tr>
                    <td style="color: var(--green); font-weight: 600;">Montant réglé</td>
                    <td class="text-right" style="color: var(--green); font-weight: 600;">{{ number_format($montantPaye, 0, ',', ' ') }} F</td>
                </tr>
                <tr>
                    <td style="font-weight: 700; color: {{ $reste > 0 ? 'var(--red)' : 'var(--text-muted)' }};">Reste à payer</td>
                    <td class="text-right" style="font-weight: 700; color: {{ $reste > 0 ? 'var(--red)' : 'var(--text-muted)' }};">
                        {{ number_format($reste, 0, ',', ' ') }} F
                    </td>
                </tr>
            </table>
        </div>

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
